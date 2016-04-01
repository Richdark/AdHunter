<?php

class Auth extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show login form
	*/
	public function login()
	{
		$this->load->helper('auth');
		
		$vars['page_title']     = 'Prihlásenie';
		$vars['invalid_fields'] = array();

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$email    = $_POST['email'];
			$password = $_POST['password'];

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
				// show view for web version
				if ($device == 'w')
				{
					array_push($vars['invalid_fields'], 'email');
					array_push($vars['invalid_fields'], 'password');
					$this->load->template('login', $vars);
				}

				// return JSON for mobile version
				else
				{
					header('Content-Type: application/json');
					echo json_encode(array('status' => 2, 'message' => 'Uvedený účet (email) nebol nájdený.'));
				}
			}
			else
			{
				foreach ($result as $row)
				{
					$user_id     = $row->id;
					$db_password = $row->password;
					$salt        = $row->salt;
				}

				$hashed_password = $this->hash_password($password, $salt);

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
						header('Content-Type: application/json');
						echo json_encode(array('status' => 1, 'message' => 'Prihlásenie úspešné.'));
					}
				}
				else
				{
					// show view for web version
					if ($device == 'w')
					{
						array_push($vars['invalid_fields'], 'password');
						$this->load->template('login', $vars);
					}

					// return JSON for mobile version
					else
					{
						header('Content-Type: application/json');
						echo json_encode(array('status' => 3, 'message' => 'Nesprávne heslo.'));
					}
				}
			}
		}
		else
		{
			$this->load->template('login', $vars);
		}
	}

	/**
	 * Process logout
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
		
		if ($device == 'm')
		{
			header('Content-Type: application/json');
			echo json_encode(array('status' => 1, 'message' => 'Odhlásenie úspešné.'));
		}
		else
		{
			header('Location: '. root_url());
		}
		
	}

	/**
	 * Show register form
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
	 * Reset user password
	*/
	public function password($code = null)
	{
		$this->load->helper('auth');

		$vars['page_title'] = 'Obnova hesla';
		$vars['invalid_fields'] = array();

		if (($code == null) and ($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$this->load->model('user_model','model');

			// handle email
			if (!(check_email($_POST['email'])))
			{
				$vars['invalid_fields']['email'] = 'invform';
			}
			
			if (!($this->model->email_exists($_POST['email'])))
			{
				$vars['invalid_fields']['email'] = 'notfound';
			}

			// email address is valid
			if (empty($vars['invalid_fields']))
			{
				$email = $_POST['email'];
				$resetcode = $this->model->set_resetcode($email);

				$this->load->library('LibPHPMailer');

				$mail = new PHPMailer();

				// use SMTP protocol
				$mail->IsSMTP();
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'ssl';
				$mail->Host = 'smtp-90181.m81.wedos.net';
				$mail->Port = 465;
				$mail->isHTML(true);

				$mail->Username = 'password@adhunter.eu';
				$mail->Password = '6aqshE_XpqwB!68K';

				$mail->setFrom('password@adhunter.eu', 'AdHunter.eu');
				$mail->addAddress($email);

				$mail->Subject = 'Obnova hesla';
				$mail->Body = 'Obnovu vášho hesla dokončíte kliknutím na <a href="http://www.adhunter.eu/auth/password/'. $resetcode. '/">tento</a> odkaz.<br /><br />---<br />Tento email bol vygenerovaný automaticky, neodpovedajte naňho.';

				if (!($mail->send()))
				{
					// echo $mail->ErrorInfo;
				}

				$vars['page_title'] = 'Email odoslaný';
				$this->load->template('password_sent', $vars);
			}
			else
			{
				$this->load->template('password', $vars);
			}
		}

		elseif ($code != null)
		{
			$this->load->model('user_model','model');

			if (($_SERVER['REQUEST_METHOD'] == 'POST') and ($code == 'save'))
			{
				// handle password
				if (!(check_password($_POST['password'])))
				{
					$vars['invalid_fields']['password'] = 'invform';
				}

				if (!($this->model->verify_resetcode($_POST['code'])) or !(check_resetcode($_POST['code'])))
				{
					$vars['invalid_fields']['resetcode'] = 'invalid';
				}

				// all fields are valid
				if (empty($vars['invalid_fields']))
				{
					$salt = $this->generate_salt(32);
					$h_password = $this->hash_password($_POST['password'], $salt);

					$this->model->update_password($_POST['code'], $h_password, $salt);
					
					$vars['page_title'] = 'Registrácia úspešná';

					$this->load->template('password_changed', $vars);
				}

				else
				{
					$vars['code'] = $_POST['code'];
					$this->load->template('password_set', $vars);
				}
			}

			else
			{
				// handle resetcode
				if (!($this->model->verify_resetcode($code)) or !(check_resetcode($code)))
				{
					$vars['invalid_fields']['resetcode'] = 'invalid';
				}

				// resetcode is valid
				if (empty($vars['invalid_fields']))
				{
					$vars['code'] = $code;
					$this->load->template('password_set', $vars);
				}

				// invalid resetcode
				else
				{
					$this->load->template('resetcode_invalid', $vars);
				}
			}
		}

		else
		{
			$this->load->template('password', $vars);
		}
	}

	/**
	 * Funkcia vyuziva standardnu hashovaciu funkciu md5 na hashovanie pouzivatelskeho hesla
	 * pred vlozenim do databazy
	 * @param string rawPasword heslo v cistom plain tvare vytiahnute z formulara
	 * @param string salt 32 bitovy nahodne generovany retazec zvysujuci bezpecnost hesla
	 * @return string hashedPassword zahesovane heslo
	*/
	private function hash_password($rawPassword, $salt)
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
	private function generate_salt($max)
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
	private function add_user()
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