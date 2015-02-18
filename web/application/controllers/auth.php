<?php

class Auth extends MY_Controller
{
	/**
	 * Funckcia zobrazi view login s prihlasovacim formularom
	*/
	public function login()
	{
		$vars['logged'] = $this->is_logged();
		$this->load->template('login', $vars);
	}

	/**
	 * Funckcia zobrazi view logout
	*/
	public function logout()
	{
		$this->logout_user();
		$vars['logged'] = $this->is_logged();
		$this->load->template('logout', $vars);
	}

	/**
	 * Funkcia zobrazi view register s registracnym formularom
	*/
	public function register()
	{
		$vars['logged'] = $this->is_logged();
		$this->load->template('register', $vars);
	}

	/**
	 * Funkcia vyuziva standardnu hashovaciu funkciu md5 na hashovanie pouzivatelskeho hesla
	 * pred vlozenim do databazy
	 * @param string rawPasword heslo v cistom plain tvare vytiahnute z formulara
	 * @param string salt 32 bitovy nahodne generovany retazec zvysujuci bezpecnost hesla
	 * @return string hashedPassword zahesovane heslo
	*/
	public function hash_password($rawPassword, $salt)
	{
		$hashedPassword = md5($rawPassword. $salt);

		return $hashedPassword;
	}

	/**
	 * Funkcia generuje nahodny x bitovy retazec tzv salt ktore znemoznuje prelamovanie hesla pomocou duhovych tabuliek
	 * retazec je zlozeny z vopred zadanych ASCII znakov
	 * @param string max maximalna dlzka generovaneho retazca
	 * @return string salt vygenerovany max znakovy retazec
	*/
	public function generate_salt($max)
	{
		$charsList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
		$i         = 0;
		$salt      = "";

		while ($i < $max)
		{
			$salt .= $charsList{mt_rand(0, (strlen($charsList) - 1))};
			$i++;
		}

		return $salt;
	}

	/**
	 * Funkcia parsuje udaje zadane pouzivatelom vo formulari zavola modelovu funkciu na ulozenie udajov do databazt
	 * a na zaver zobrazi udaj o uspesnosti registracie
	*/
	public function add_user()
	{
		$email    = $_POST['email'];
		$password = $_POST['password'] ;
		$name     = $_POST['name'];
		$surname  = $_POST['surrname'];
		$salt     = $this->generate_salt(32);

		$hashed_password = $this->hash_password($password,$salt);

		$this->load->model('user_model','model');
		$this->model->save_user('DEFAULT',$name,$surname,$email,$hashed_password,$salt);
		$vars['logged'] = $this->is_logged();
		$this->load->template('registration_successful', $vars);
	}

	/**
	 * Funkcia overujuca spravnost zadanych prihlasovacich udajov na zaklade udajov poskytnutych
	 * v prihlasovacom formulari pokial su spravne zobrzi view o uspesnosti prihlasenia v opacnom pripade o neuspesnosti
	*/
	// POST http://adhunter.eu/auth/login_user/ email&password&uid
	public function login_user()
	{
		$email          = $_POST['email'];
		$typed_password = $_POST['password'];
		$type           = parent::$type;

		if ($type == 'w')
		{
			@session_start();
			$uid = session_id();
		}
		else
		{
			$uid = $_POST['uid'];
		}

		$this->load->model('user_model','model');
		
		$result  = $this->model->get_user_by_login($email);
		$row_cnt = sizeof($result);
		
		$vars['logged'] = $this->is_logged();
		
		if ($row_cnt == 0)						// login not found
		{
			$this->load->template('login_failed', $vars);
		}
		else
		{
			foreach ($result as $row)
			{
				$user_id     = $row->id;
				$db_password = $row->password;
				$salt        = $row->salt;
			}

			$hashed_password = $this->hash_password($typed_password,$salt);

			if ($db_password == $hashed_password)
			{
				$this->Online_user_model->login_user('DEFAULT',$user_id,$uid,$type);
				$vars['logged'] = $this->is_logged();
				if ($type == 'w') {
					$this->load->template('login_successful', $vars);
				} else {
					echo "OK";
				}
			}
			else
			{
				$this->load->template('login_failed', $vars);
			}
		}
	}

	/**
	 * Funkcia na odhlasenie pouzivatela
	*/
	// POST http://adhunter.eu/auth/logout_user/ uid
	public function logout_user()
	{
		$type = parent::$type;
		if ($type == 'w')
		{
			@session_start();
			$uid = session_id();
		}
		else
		{
			$uid = $_POST['uid'];
		}
		$this->Online_user_model->logout_user($uid,$type);
		if ($type != 'w') {
			echo "OK";
		}
	}
}

?>