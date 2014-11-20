<?php
class Login_Registration extends CI_controller
{

public function login()
{
$this->load->view('login');
}

public function register()
{
$this->load->view('register');
}

public function hash_password($rawPassword,$salt){
	$hashedPassword= md5($rawPassword.$salt);

	return $hashedPassword;

}

public function generate_salt($max){
	   $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
}

public function add_user(){
	
$email=$_POST['email'];
$password=$_POST['password'] ;
$name =$_POST['name'];
$surname=$_POST['surrname'];
$salt = $this->generate_salt(32);

$hashed_password=$this->hash_password($password,$salt);

$this->load->model('Registration_model','model');
$this->model->save_user('DEFAULT',$name,$surname,$email,$hashed_password,$salt);

$this->load->view('registration_successful');

}

public function authentificate_user(){
	$email=$_POST['email'];
	$typed_password=$_POST['password'] ;

	$this->load->model('Registration_model','model');
	$result=$this->model->get_password_for_login($email);
		
	$row_cnt = sizeof($result);
	if($row_cnt==0){
		$this->load->view('login_failed');
	}
	else{
	$db_password;
	$salt;
	foreach($result as $row) {
	$db_password=$row->heslo;
	$salt=$row->salt;
	}

	$hashed_password=$this->hash_password($typed_password,$salt);

	if($db_password==$hashed_password){
		$this->load->view('login_successful');
	}
	
	else{
		$this->load->view('login_failed');
	}
	
}
		
		
		
	
	
}


}

?>