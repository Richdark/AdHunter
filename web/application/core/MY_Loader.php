<?php

class MY_Loader extends CI_Loader
{
    /**
     * Custom view loader
     *
     * Three versions of layout - 'regular', 'map' or 'landing'
    */
    public function template($template_name, $vars = array(), $version = 'regular', $return = false)
    {
        // default title
        if (!(isset($vars['title'])))
        {
            $vars['title'] = 'AdHunter';
        }

        $vars['layout_version'] = (in_array($version, array('regular', 'map', 'landing'))? $version : 'regular');

        // load header
        $content = $this->view('header', $vars, $return);

        // load body
        $content .= $this->view($template_name, $vars, $return);

        // load footer
        $content .= $this->view('footer', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }
}