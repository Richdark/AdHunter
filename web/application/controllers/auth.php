<?php

class Auth extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Funckcia zobrazi view login s prihlasovacim formularom
	*/
	public function login()
	{
		$this->load->helper('auth');
		
		$vars['page_title']     = 'Prihlásenie';
		$vars['invalid_fields'] = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$email          = $_POST['email'];
			$typed_password = $_POST['password'];

			// login from mobile application
			if (isset($_POST['uid']))
			{
				$device = 'm';
				$uid    = $_POST['uid'];
			}
			else
			{
				$device = 'w';
				$uid    = session_id();
			}

			$this->load->model('user_model','model');
			
			$result  = $this->model->get_user_by_login($email);
			$row_cnt = sizeof($result);
			
			// login not found
			if ($row_cnt == 0)
			{
				array_push($vars['invalid_fields'], 'email');
				array_push($vars['invalid_fields'], 'password');
				$this->load->template('login', $vars);
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
					$this->Online_user_model->login_user('DEFAULT', $user_id, $uid, $device);

					// show view for web version
					if ($device == 'w')
					{
						header('Location: '. root_url());
					}

					// return JSON for mobile version
					else
					{
						echo "OK";
					}
				}
				else
				{
					array_push($vars['invalid_fields'], 'password');
					$this->load->template('login', $vars);
				}
			}
		}
		else
		{
			$this->load->template('login', $vars);
		}
	}

	/**
	 * Funckcia zobrazi view logout
	*/
	public function logout()
	{
		// login from mobile application
		if (isset($_POST['uid']))
		{
			$device = 'm';
			$uid    = $_POST['uid'];
		}
		else
		{
			$device = 'w';
			$uid    = session_id();
		}

		$this->Online_user_model->logout_user($uid);
		
		if ($device != 'w')
		{
			echo "OK";
		}
		else
		{
			header('Location: '. root_url());
		}
		
	}

	/**
	 * Funkcia zobrazi view register s registracnym formularom
	*/
	public function register()
	{
		$this->load->helper('auth');
		
		$vars['page_title']     = 'Registrácia';
		$vars['invalid_fields'] = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			// handle email
			if (!(check_email($_POST['email'])))
			{
				$vars['invalid_fields']['email'] = 'invform';
			}

			$this->load->model('user_model','model');

			if ($this->model->email_exists($_POST['email']))
			{
				$vars['invalid_fields']['email'] = 'alrdreg';
			}

			// handle password
			if (!(check_password($_POST['password'])))
			{
				$vars['invalid_fields']['password'] = 'invform';
			}
			
			// all fields are valid
			if (empty($vars['invalid_fields']))
			{
				$email      = $_POST['email'];
				$password   = $_POST['password'] ;
				$name       = $_POST['name'];
				$surname    = $_POST['surrname'];
				$salt       = $this->generate_salt(32);
				$h_password = $this->hash_password($password, $salt);

				$this->model->save_user('DEFAULT', $name, $surname, $email, $h_password, $salt);
				
				$vars['page_title'] = 'Registrácia úspešná';
				$this->load->template('registration_successful', $vars);
			}

			// one or more fields are invalid
			else
			{
				$this->load->template('register', $vars);
			}
		}
		else
		{
			$this->load->template('register', $vars);
		}
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
		$this->load->template('registration_successful', $vars);
	}
}

?>