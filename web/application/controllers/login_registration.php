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

public function generate_salt(){
	$salt = openssl_random_pseudo_bytes(32);

	return $salt;
}

public function add_user(){
	
$email=$_POST['email'];
$password=$_POST['password'] ;
$name =$_POST['name'];
$surname=$_POST['surrname'];
$salt = $this->generate_salt();

$hashedPassword=$this->hash_password($password,$salt);

$this->load->model('Registration_model','model');
$this->model->save_user('DEFAULT',$name,$surname,$email,$password,$salt);

}



}
?>