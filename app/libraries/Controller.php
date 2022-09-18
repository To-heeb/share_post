<?php

/** 
 * Base Controller class
 * Load the models and views
 */

class Controller
{
    //Load models
    public function model($model)
    {
        // Require the model file
        require_once '../app/models/' . $model . '.php';

        //Instantiate the model
        return new $model();
    }

    //Load view
    public function view($view, $data = [])
    {
        // Check for view file
        if (file_exists('../app/views/' .  $view . '.php')) {
            require_once '../app/views/' .  $view . '.php';
        } else {
            //View doesn't exit
            die('View file not founds');
        }
    }


    //Load template
    public function template($view, $data = [])
    {
        $this->view($view['header'], $data);
        $this->view($view['page'], $data);
        $this->view($view['footer'], $data);
    }
}
