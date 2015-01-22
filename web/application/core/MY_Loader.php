<?php

class MY_Loader extends CI_Loader
{
    public function template($template_name, $vars = array(), $return = false)
    {
        // default title
        if (!(isset($vars['title'])))
        {
            $vars['title'] = 'AdHunter';
        }

        // load header
        $content = $this->view('header', $vars, $return);

        // load body
        $content .= $this->view($template_name, $vars, $return);

        // load footer
        // $content .= $this->view('footer', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }
}