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



}
?>