<?php

class Posts extends Controller
{

    public function __construct()
    {
        if (!is_logged_in()) redirect('users/login');

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $posts = $this->postModel->getPosts();
        $data = [
            'posts' => $posts,
        ];

        $this->view('inc/header', $data);
        $this->view('posts/index', $data);
        $this->view('inc/footer', $data);
    }

    public function add()
    {

        //Check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Process form

            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [

                'title' =>  trim($_POST['title']),
                'body' =>  trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_error' => '',
                'body_error' => '',
                'error' => [],
            ];


            //Validate title
            if (empty($data['title'])) {
                $data['title_error'] = 'Please enter title.';
                array_push($data['error'], $data['title_error']);
            }

            //Validate password
            if (empty($data['body'])) {
                $data['body_error'] = 'Please enter body text.';
                array_push($data['error'], $data['body_error']);
            }


            //Make sure  errors are empty
            if (empty($data['error'])) {
                //Validated
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'Post added successfully.');
                    redirect('posts');
                } else {
                    flash('post_message', 'Post addition failed, please try again later', 'alert alert-danger');
                    redirect('posts');
                }
            } else {
                $this->view('inc/header', $data);
                $this->view('posts/add', $data);
                $this->view('inc/footer', $data);
            }
        } else {
            //Init data

            $data = [
                'title' => '',
                'body' => '',
            ];

            $this->view('inc/header', $data);
            $this->view('posts/add', $data);
            $this->view('inc/footer', $data);
        }
    }

    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user,
            'user_id' => $_SESSION['user_id'],
        ];

        $this->view('posts/show', $data);
    }

    public function edit($id)
    {
        //Check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Process form

            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [

                'title' =>  trim($_POST['title']),
                'body' =>  trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'id' => $id,
                'title_error' => '',
                'body_error' => '',
                'error' => [],
            ];


            //Validate title
            if (empty($data['title'])) {
                $data['title_error'] = 'Please enter title.';
                array_push($data['error'], $data['title_error']);
            }

            //Validate password
            if (empty($data['body'])) {
                $data['body_error'] = 'Please enter body text.';
                array_push($data['error'], $data['body_error']);
            }


            //Make sure  errors are empty
            if (empty($data['error'])) {
                //Validated
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post updated successfully.');
                    redirect('posts');
                } else {
                    flash('post_message', 'Post updating failed, please try again later', 'alert alert-danger');
                    redirect('posts');
                }
            } else {
                $this->view('posts/edit', $data);
            }
        } else {
            //Init data
            $post = $this->postModel->getPostById($id);
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }

            $data = [
                'id' => $post->id,
                'title' =>  $post->title,
                'body' => $post->body,
            ];


            $this->view('posts/edit', $data);
        }
    }

    public function delete($id)
    {
        //Check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->postModel->getPostById($id);
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            if ($this->postModel->deletePost($id)) {
                flash('post_message', 'Post deleted successfully.');
                redirect('posts');
            } else {
                flash('post_message', 'Post deleting failed, something went wrong please try again later', 'alert alert-danger');
                redirect('posts');
            }
        }
    }
}
