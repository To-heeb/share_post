<?php

/**
 * App Core Class
 * Creates Url & loads core controller
 * URL FORMAT - /controller/method/parameters
 */

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $parameters = array();

    public function __construct()
    {


        $url = $this->getUrl();
        #Dump::dd($url);
        $this->parseUrl($url);
    }



    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }

    public function parseUrl($url)
    {
        //Look in controller for first value
        if (!is_null($url) and file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            //if exists, set as controller class
            $this->currentController = ucwords($url[0]);

            //Unset 0 Index
            unset($url[0]);
        }
        // Redirect if files don't exists to a 404 page

        //Require controller class
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            //check if method exists
            if (method_exists($this->currentController, $url[1]) && $url[1] !== 'index') {
                $this->currentMethod = $url[1];

                //Unset 1 Index
                unset($url[1]);
            }
        }

        // Get Parameters
        $this->parameters = $url ? array_values($url) : array();

        // Call a callback with array of parameters
        call_user_func_array((array)array($this->currentController, $this->currentMethod), $this->parameters);
    }
}
