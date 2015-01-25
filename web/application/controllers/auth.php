<?php

class Auth extends CI_controller
{
	/**
	 * Funckcia zobrazi view login s prihlasovacim formularom
	*/
	public function login()
	{
		$this->load->template('login');
	}

	/**
	 * Funckcia zobrazi view logout
	*/
	public function logout()
	{
		$this->session->sess_destroy();

		$this->load->template('logout');
	}

	/**
	 * Funkcia zobrazi view register s registracnym formularom
	*/
	public function register()
	{
		$this->load->template('register');
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
		$this->load->template('registration_successful');
	}

	/**
	 * Funkcia overujuca spravnost zadanych prihlasovacich udajov na zaklade udajov poskytnutych
	 * v prihlasovacom formulari pokial su spravne zobrzi view o uspesnosti prihlasenia v opacnom pripade o neuspesnosti
	*/
	public function authentificate_user()
	{
		$email          = $_POST['email'];
		$typed_password = $_POST['password'] ;

		$this->load->model('user_model','model');
		
		$result  = $this->model->get_password_for_login($email);
		$row_cnt = sizeof($result);
		
		if ($row_cnt == 0)
		{
			$this->load->template('login_failed');
		}
		else
		{
			foreach ($result as $row)
			{
				$db_password = $row->password;
				$salt        = $row->salt;
			}

			$hashed_password = $this->hash_password($typed_password,$salt);

			if ($db_password == $hashed_password)
			{
				$this->load->template('login_successful');

				$this->session->set_userdata(array('email' => $email));
			}
			else
			{
				$this->load->template('login_failed');
			}
		}
	}
}

?>