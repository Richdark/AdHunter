<?php

/**
 * obsahuje funkcie pre pracu s billboardami
*/
class Billboards extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * funkcia zobrazi view s hlavnou ponukou
	*/
	public function index()
	{
		$this->load->template('home_page', NULL, 'landing');
	}

	/**
	 * funkcia vracia zoznam vsetkych ulovkov
	 *
	 * @return object $json funkcia vracia zoznam vsetkych ulovkov aj s dodatocnymi informaciami
	*/
	public function get_catches()
	{
		header('Content-type: application/json');

		$user_id = $this->user->logged ? $this->user->id : -1;
		$this->load->model('Catch_model', 'model');
		$result = $this->model->get_all($user_id);

		$json = array();
		
		foreach ($result as $row)
		{
			$json[] = $row;
		}

		echo json_encode($json);
	}

	/**
	 * zluci ulovky
	*/
	public function merge_catches()
	{
		// catch state:
		//    '0' => merged into different catch
		//    '1' => default (not merged)
		
		if ($this->user->logged)
		{
			$user_id = $this->user->id;

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
		header('Content-type: application/json');

		$ret = array();
		$ret["status"] = "error";
		$ret["message"] = "";

		if (empty($_FILES["photo"]))
		{
			$ret["message"] = "Nebola prijata ziadna fotografia";
			die(json_encode($ret));
		}

		// musi to byt realna cesta k suboru nie cez assets_url
		$folder = __DIR__ . "/../../assets/pics";
		$name   = $this->_get_filename($folder, $_FILES["photo"]["name"]);

		// vytvor rekurzivne dany folder ak neexistuje
		if (!file_exists($folder))
		{
			mkdir($folder, 0777, true);
		}

		if (!is_writable($folder))
		{
			$ret["message"] = "Priecinok nieje zapisovatelny";
			die(json_encode($ret));
		}

		$lat = &$_POST["lat"];
		$lng = &$_POST["lng"];
		if(empty($lat) || empty($lng)) {
			$ret["message"] = "Neboli zadané GPS suradnice";
			die(json_encode($ret));
		}
		$coordinates = "POINT($lat, $lng)";
		
		// destination filename
		$dest_name = $folder. '/'. $name;

		// move z tmp foldra
		if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $dest_name))
		{
			$ret["message"] = "Nepodarilo sa uploadovať billboard na server";
			die(json_encode($ret));
		}

		// rotate image if needed
		$exif_data = read_exif_data($dest_name);

		// image orientation is not default
		if (isset($exif_data['Orientation']) and $exif_data['Orientation'] != 1)
		{
			// load image
			$original_image = imagecreatefromjpeg($dest_name);

			// find rotation angle
			switch ($exif_data['Orientation'])
			{
				// rotate 180 degrees
				case 3: $angle = 180;
					break;

				// rotate clockwise
				case 6: $angle = -90;
					break;

				// rotate counter-clockwise
				case 8: $angle = 90;
					break;
				
				// do not rotate
				default: $angle = 0;
					break;
			}

			$rotated_image = imagerotate($original_image, $angle, 0);

			// save rotated image
			imagejpeg($rotated_image, $dest_name);
		}

		// ulovok prisiel z mobilneho zariadenia
		if (!empty($_POST["model"]))
		{
			$model = $_POST["model"];
			$type = 'm';
		}
		else
		{
			$model = null;
			$type = 'w';
		}

		$comment = !empty($_POST["comment"]) ? htmlspecialchars($_POST["comment"]) : null;
		$backing_type = !empty($_POST["backing_type"]) ? $_POST["backing_type"] : null;
		$owner_id = !empty($_POST["owner_id"]) ? $_POST["owner_id"] : null;
		$user_id = $this->user->logged ? $this->user->id : null;

		// vlozenie do databazy prostrednictvom modelu
		$this->load->model('Catch_model', 'model');
		$this->model->save_catch($user_id, null, $coordinates, $name, $model, $type, $comment, $backing_type, $owner_id);
		
		if(!empty($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
			die;
		}

		$ret["status"] = "ok";
		die(json_encode($ret));
	}

	/*
	 * update billboardu
	 */
	public function update()
	{
		if(empty($_POST["catch_id"]) || !$this->user->logged) {
			die("error");
		}

		$catch_id = $_POST["catch_id"];
		$comment = !empty($_POST["comment"]) ? $_POST["comment"] : null;
		$owner_id = isset($_POST["owner_id"]) ? $_POST["owner_id"] : null;
		$backing_type = isset($_POST["backing_type"]) ? $_POST["backing_type"] : null;

		$this->load->model('Catch_model', 'model');
		$this->model->update_catch($catch_id, $comment, $owner_id, $backing_type);

		echo "OK";
	}

	/*
	 * premiestnenie billboardu
	 */
	public function move()
	{
		if(empty($_POST["catch_id"]) || !$this->user->logged) {
			die("error");
		}

		$catch_id = $_POST["catch_id"];
		$lat = &$_POST["lat"];
		$lng = &$_POST["lng"];
		$coordinates = "POINT($lat, $lng)";

		$this->load->model('Catch_model', 'model');
		$this->model->move_catch($catch_id, $coordinates);

		echo "OK";
	}

	/*
	 * zmazanie billboardu
	 */
	public function delete()
	{
		if(empty($_POST["catch_id"]) || !$this->user->logged) {
			die("error");
		}

		$catch_id = $_POST["catch_id"];
		$this->load->model('Catch_model', 'model');
		$this->model->delete_catch($catch_id);

		echo "OK";
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
		$vars   = array(
			'page_title' => 'Mapa reklám',
			'owners'     => $owners
		);

		$this->load->template('show_billboard', $vars, 'map');
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