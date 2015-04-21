<?php

class Profile extends MY_Controller {

    /**
     * User account settings
     */
    public function index() {
        $vars['page_title'] = 'Nastavenia účtu';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

        // form was sent
        if (isset($_POST['send'])) {
            $name = $_POST['name'];
            $surname = $_POST['surname'];

            $this->load->model('User_model');
            $this->User_model->set_user_info($this->user->id, $name, $surname);

            $vars['success'] = true;
            $vars['name'] = $this->user->name = $name;
            $vars['surname'] = $this->user->surname = $surname;
        } else {
            $vars['name'] = $this->user->name;
            $vars['surname'] = $this->user->surname;
        }

        $this->load->template('profile_main', $vars);
    }

    /**
     * User added catches list
     */
    public function catches() {
        $vars['page_title'] = 'Moje úlovky';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

        $this->load->model('Catch_model');
        $vars['catches_list'] = $this->Catch_model->get_catches_by_user_id($this->user->id);

        $this->load->template('profile_catches', $vars);
    }

    public function badges() {
        $vars['page_title'] = 'Moje Odznaky';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);
        
        $this->load->model('Gamification_model', 'model');
        $user_id = $this->user->id;
        $vars['statistic'] = $this->model->get_all_by_id($user_id);
        $vars['types'] = $this->model->get_types_by_id($user_id);
        $vars['sources'] = $this->model->get_sources_by_id($user_id);
        $vars['owners'] = $this->model->get_owners_by_id($user_id);
    
        $this->load->template('profile_badges', $vars);
    }

}

?>