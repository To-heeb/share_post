<?php

class Pages extends Controller
{


    public function __construct()
    {
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect('posts');
        }
        $data = [
            'title' => 'Shareposts',
            'description' => 'Simple social network built on smallie_mvc PHP framework',
        ];

        $this->view('inc/header', $data);
        $this->view('pages/index', $data);
        $this->view('inc/footer', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Us',
            'description' => 'App to share with other users.',
        ];

        $this->view('pages/about', $data);
    }
}
