<?php

class MY_Controller extends CI_Controller
{
    public static $type = 'w';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Online_user_model');
        $ret = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet'.
        '|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]'.
        '|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT']);

        self::$type = $ret ? 'm' : 'w';
        
        @session_start();
    }

    public function is_logged()
    {
        return $this->Online_user_model->is_logged(session_id(), self::$type);
    }

    public function get_user_id()
    {
        return $this->Online_user_model->get_user_id(session_id());
    }
}