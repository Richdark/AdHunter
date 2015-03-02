<?php

/**
 * obsahuje funkcie pre pracu s billboardami
*/
class Billboards extends MY_Controller
{
	/**
	 * funkcia zobrazi view s hlavnou ponukou
	*/
	public function index()
	{
		$vars['logged'] = $this->is_logged();
		$this->load->template('home_page', $vars);
	}

	/**
	 * funkcia vracia zoznam vsetkych ulovkov
	 *
	 * @return object $json funkcia vracia zoznam vsetkych ulovkov aj s dodatocnymi informaciami
	*/
	public function get_catches()
	{
		header('Content-type: application/json');

		$this->load->model('Catch_model', 'model');
		$result = $this->model->get_all();

		$json = array();
		foreach ($result as $row)
		{
			$json[] = $row;
		}

		echo json_encode($json);
	}

	/**
	 * ulokov podla ID
	 *
	 * @return object $json funkcia vracia ulovok aj s dodatocnymi informaciami
	*/
	public function get_catch($id)
	{
		header('Content-type: application/json');
		
		$this->load->model('Catch_model', 'model');
		$result = $this->model->get_catch_by_id($id);

		echo json_encode($result[0]);
	}

	/**
	 * zluci ulovky
	*/
	public function merge_catches()
	{
		// catch state:
		//    '0' => merged into different catch
		//    '1' => default (not merged)
		
		if ($this->is_logged())
		{
			$user_id = $this->get_user_id();

			$main   = (is_numeric($_GET['main']))? $_GET['main'] : -1;
			$merged = $_GET['merged'];
			$merged = explode(',', $merged);

			foreach ($merged as $key => $value)
			{
				if (!(is_numeric($value)))
				{
					$merged[$key] = -1;
				}
			}

			$this->load->model('Catch_model', 'model');
			$merged = $this->model->merge_catches($user_id, $main, $merged) + 1;

			echo 'zlúčených '. $merged. ' úlovkov';
		}
		else
		{
			echo 'pre zlučovanie úlovkov sa musíte prihlásiť';
		}
	}

	/*
	 * pridanie billboardu
	 */
	public function add()
	{
		if (empty($_FILES["photo"]))
		{
			echo "Chyba: Nebola prijata ziadna fotografia<br>";
			die;
		}

		$folder = __DIR__ . "/../../assets/pics";	// musi to byt realna cesta k suboru nie cez assets_url
		$name   = $this->_get_filename($folder, $_FILES["photo"]["name"]);

		// vytvor rekurzivne dany folder ak neexistuje
		if (!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}

		if (!is_writable($folder))
		{
			echo "Chyba: Priecinok nieje zapisovatelny<br>";
			die;
		}

		$lat = &$_POST["lat"];
		$lng = &$_POST["lng"];
		if(empty($lat) || empty($lng)) {
			echo "Chyba: Neboli zadané GPS suradnice<br>";
			die;
		}
		$coordinates = "POINT($lat, $lng)";
			
		// move z tmp foldra
		if (!move_uploaded_file($_FILES["photo"]["tmp_name"], "$folder/$name"))
		{		
			echo "Chyba: Nepodarilo sa uploadovať billboard na server<br>";
			die;
		}

		if (!empty($_POST["model"]))
		{
			$model = $_POST["model"];
			$type = 'm';		// ulovok prisiel z mobilneho zariadenia
		}
		else
		{
			$model = null;
			$type = 'w';
		}

		/**
		 * @todo typ nosica sa neuklada do db a ani nezobrazuje, treba mu vytvorit column
		 */
		$backing_type = !empty($_POST["backing_type"]) ? $_POST["backing_type"] : 1;
		$comment = !empty($_POST["comment"]) ? htmlspecialchars($_POST["comment"]) : null;

		// vlozenie do databazy prostrednictvom modelu
		$this->load->model('Catch_model', 'model');
		$this->model->save_catch(1, 1, $coordinates, $name, $model, $type, $comment);
		
		if ($type == 'm')
		{
			echo "OK";
		}
		else
		{
			$this->load->view('uploaded_billboard');
		}
	}

	/**
	 * funkcia sluzi na zobrazenie billboardov
	 * 
	 * tato funkcia je volana z webovej i mobilnej aplikacie, pricom sa vzdy vratia data rovnakeho formatu
	 * vo funkcii sa spracovavaju globalne premenne $_POST["lat"], $_POST["lng"] a $_FILES["photo"]
	 * - v pripade, ze je funkcia volana bez potrebnych parametrov, zobrazi sa view pre pridanie billboardu
	 * vo webovom prostredi
	*/
	public function show()
	{
		$this->load->model('Owner_model', 'model');
		$owners = $this->model->get_all();
		$vars = array(
			'owners'  => $owners,
			'logged'  => $this->is_logged(),
			'user_id' => $this->get_user_id()
		);

		$this->load->template('show_billboard', $vars);
	}

	// ------------------
	// private functions
	// ------------------

	/**
	 * funkcia na ocistenie nazvu suboru
	 *
	 * funkcia ocisti meno suboru od specialnych znakov, znak medzery je nahradeny znakom '-' a
	 * znaky s diakritikou su nahradene ich ekvivalentom bez diakritiky (á => a)
	 *
	 * @param string $name nazov suboru obsahujuci zakazane znaky
	 *
	 * @return string funkcia vracia nazov suboru ocisteneho od specialnych znakov
	*/
	private function _clean_name(&$name)
	{
		$table = array(
			" " => "-",
			"\xc3\xa1" => "a", "\xc3\xa4" => "a", "\xc4\x8d" => "c", "\xc4\x8f" => "d", "\xc3\xa9" => "e", "\xc4\x9b" => "e", "\xc3\xad" => "i", "\xc4\xbe" => "l", 
			"\xc4\xba" => "l", "\xc5\x88" => "n", "\xc3\xb3" => "o", "\xc3\xb6" => "o", "\xc5\x91" => "o", "\xc3\xb4" => "o", "\xc5\x99" => "r", "\xc5\x95" => "r", 
			"\xc5\xa1" => "s", "\xc5\xa5" => "t", "\xc3\xba" => "u", "\xc5\xaf" => "u", "\xc3\xbc" => "u", "\xc5\xb1" => "u", "\xc3\xbd" => "y", "\xc5\xbe" => "z"
		);

		$name = mb_strtolower($name, "utf-8");
		$name = strtr($name, $table);

		return preg_replace("/[^A-Za-z0-9\-\_\.]/", "", $name);
	}

	/**
	 * funkcia vracia nazov uploadovaneho suboru
	 *
	 * funkcia ocisti meno suboru a ak sa na serveri subor s danym nazvom uz nachadza prida mu
	 * prisluchajuci postfix (_2)
	 *
	 * @param string $flder priecinok, do ktoreho sa uploaduje subor
	 * @param string $name nazov uploadovaneho suboru
	 *
	 * @return string nazov uploadovaneho suboru
	*/
	private function _get_filename(&$folder, &$name)
	{
		$name = $this->_clean_name($name);
		$ext  = pathinfo($name, PATHINFO_EXTENSION);
		$base = basename($name, ".$ext");

		for ($i = 1; file_exists("$folder/$name"); $i++)
		{
			$name = $base.($i < 2 ? "" : "_$i").".$ext";
		}

		return $name;
	}
}

?>