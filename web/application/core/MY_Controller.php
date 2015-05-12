<?php

class MY_Controller extends CI_Controller
{
    /**
     * Current user
    */
    public $user;

    public function __construct()
    {
        parent::__construct();

        $this->user = new UserModelHelper();
        $this->load->model('Online_user_model');
        
        if (!empty($_POST["uid"]))      // mobil device
        {
            $this->user->device_type = 'm';
            $this->user->session_id = $_POST["uid"];
        }
        else                            // web user
        {
            $this->user->device_type = 'w';
            $this->user->session_id = session_id();
        }

        /*if (array_key_exists('HTTP_USER_AGENT', $_SERVER))
        {
            $ret = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
            '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
            '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);

            $this->user->device_type = $ret ? 'm' : 'w';
        }*/
        
        @session_start();

        // fill user model with data
        $this->user->logged = $this->Online_user_model->is_logged(session_id());
        $user_info          = $this->Online_user_model->get_user_info(session_id());
        
        if ($user_info)
        {
            $this->user->id      = $user_info->user_id;
            $this->user->name    = $user_info->name;
            $this->user->surname = $user_info->surname;
            $this->user->email   = $user_info->email;
        }
        
        if ($this->user->logged)
        {
            $this->load->model('Gamification_model');
            $this->user->billboards = $this->Gamification_model->get_level_by_id($this->user->id);
        }

        $this->load->user = $this->user;
    }
}