<?php

class Profile extends MY_Controller {

    /**
     * User account settings
     */
    public function index()
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

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
    public function catches()
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

        $vars['page_title'] = 'Moje úlovky';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

        $this->load->model('Catch_model');
        $vars['catches_list'] = $this->Catch_model->get_catches_by_user_id($this->user->id);

        $this->load->template('profile_catches', $vars);
    }

    public function badges()
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

        $vars['page_title'] = 'Moje ocenenia';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);
        
        $this->load->model('Gamification_model', 'model');
        $user_id = $this->user->id;
        
        $vars['medals'] = $this->model->get_medals_by_user($user_id);
        /*
        $vars['statistic'] = $this->model->get_all_by_id($user_id);
        $vars['types'] = $this->model->get_types_by_id($user_id);
        $vars['sources'] = $this->model->get_sources_by_id($user_id);
        $vars['owners'] = $this->model->get_owners_by_id($user_id);
        */

        $this->load->template('profile_badges', $vars);
    }
    
    
    public function statistics()
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

        $vars['page_title'] = 'Štatistiky';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);
        
        $this->load->model('Gamification_model', 'model');
        $user_id = $this->user->id;
        $vars['activity'] = $this->model->get_activity_by_id($user_id);
        $vars['top_ten'] = $this->model->get_top_10($user_id);
        $vars['record'] = $this->model->get_best_activity_by_id($user_id);
        $vars['order'] = $this->model->get_order_by_id($user_id);
        
        $this->load->template('profile_statistics', $vars);
    }
    
    

    public function merges()
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

        $vars['page_title']   = 'Zlučovanie úlovkov';
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

        $this->load->model('Catch_model');
        $vars['candidates'] = $this->Catch_model->get_merge_candidates();
        $vars['candidates'] = $this->filter_merge_candidates($vars['candidates']);

        $this->load->template('profile_merges_suggestions', $vars);
    }

    /**
     * Resolve merge suggestions
     *
     * @param integer $c1 ID of first merge candidate
     * @param integer $c2 ID of second merge candidate
     * @param string $verdict 1 if images are the same, 0 otherwise
     *
     * @return array Array containing new pair of merge candidates
     */
    public function resolve_merge_candidates($c1, $c2, $verdict)
    {
        if (!($this->user->logged))
        {
            header('Location: '. root_url());
        }

        $this->load->model('Catch_model');
        $this->Catch_model->resolve_merge_candidates($this->user->id, $c1, $c2, $verdict);

        $candidates = $this->Catch_model->get_merge_candidates();
        $candidates = $this->filter_merge_candidates($candidates);

        header('Content-type: application/json');
        echo json_encode($candidates);
    }

    /**
     * Get two merge candidates from the input list of all candidates
     *
     * @param array $candidates Array containing two or more merge candidates
     *
     * @return array Array containing final two merge candidates
     */
    private function filter_merge_candidates($candidates)
    {
        $probabilities = array();
        $prob_vector   = 0;
        $candidates_c  = count($candidates);

        // exclude first - this will be compared to each other
        for ($i = 1; $i < $candidates_c; $i++)
        {
            $probabilities[$i] = 0;
        
            // same user, different other data
            if (($candidates[0]->user_id == $candidates[$i]->user_id) and
                ($candidates[0]->backing_type_id != $candidates[$i]->backing_type_id) and
                ($candidates[0]->owner_id != $candidates[$i]->owner_id))
            {
                $probabilities[$i] = 0.05;
            } 
            
            // different user, same other data
            else if (($candidates[0]->user_id != $candidates[$i]->user_id) and
                ($candidates[0]->backing_type_id == $candidates[$i]->backing_type_id) and
                ($candidates[0]->owner_id == $candidates[$i]->owner_id))
            {
                $probabilities[$i] = 0.7;
            }
            
            // same user
            else if ($candidates[0]->user_id == $candidates[0]->user_id)
            {
                $probabilities[$i] = 0.3;
            }

            // other scenarios
            else
            {
                $probabilities[$i] = 0.5;
            }

            $prob_vector += $probabilities[$i];
        }

        $rand_vector_pos = rand(0, $prob_vector * 10) / 10;
        $curr_vector_pos = 0;
        $final_candidate = NULL;

        foreach ($probabilities as $candidate => $probability)
        {
            $final_candidate =  $candidate;
            $curr_vector_pos += $probability;

            if ($curr_vector_pos >= $rand_vector_pos)
            {
                break;
            }
        }

        return array(array('id' => $candidates[0]->id, 'filename' => $candidates[0]->filename), array('id' => $candidates[$final_candidate]->id, 'filename' => $candidates[$final_candidate]->filename));
    }

    /**
     * Manage owners
     *
     * @param string $method Whether app should add new, edit existing or list all current owners
     */
    public function owners($method)
    {
        $vars['profile_menu'] = $this->load->view('profile_menu', NULL, true);

        if ($method == 'add')
        {
            $vars['page_title'] = 'Pridať nového vlastníka';

            // form sent
            if (isset($_POST['send']))
            {
                $vars['invalid_fields'] = array();

                if ($_POST['name'] != NULL)
                {
                    $name = $_POST['name'];
                }
                else
                {
                    $vars['invalid_fields']['name'] = 'empty';
                }

                // all fields are valid
                if (empty($vars['invalid_fields']))
                {
                    $this->load->model('Owner_model');
                    $this->Owner_model->add_owner($name);

                    $vars['success'] = true;
                }

            }
            
            $this->load->template('add_owner', $vars);
        }
    }
}

?>