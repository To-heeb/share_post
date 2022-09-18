<?php

class Users extends Controller
{
    public function __construct()
    {
        $this->userModel  = $this->model('User');
    }


    public function register()
    {
        //Check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Process form


            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [
                'name' => trim($_POST['name']),
                'email' =>  trim($_POST['email']),
                'password' =>  trim($_POST['password']),
                'confirm_password' =>  trim($_POST['confirm_password']),
                'name_error' => '',
                'email_error' => '',
                'password_error' => '',
                'confirm_password_error' => '',
                'error' => [],
            ];


            //Validate email address
            if (empty($data['email'])) {
                $data['email_error'] = 'Please enter your email address.';
                array_push($data['error'], $data['email_error']);
            } else {

                //Check to see if email already exists
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_error'] = 'Email is already taken.';
                    array_push($data['error'], $data['email_error']);
                }
            }

            //Validate name
            if (empty($data['name'])) {
                $data['name_error'] = 'Please enter your name.';
                array_push($data['error'], $data['name_error']);
            }

            //Validate password
            if (empty($data['password'])) {
                $data['password_error'] = 'Please enter your password.';
                array_push($data['error'], $data['password_error']);
            } elseif (strlen($data['password']) > 6) {
                $data['password_error'] = 'Password must be atleast 6 characters.';
                array_push($data['error'], $data['password_error']);
            }

            //Validate Confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_error'] = 'Please confirm password.';
                array_push($data['error'], $data['confirm_password_error']);
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_error'] = 'Password do not match.';
                    array_push($data['error'], $data['confirm_password_error']);
                }
            }


            //Make sure  errors are empty
            if (empty($data['error'])) {
                //Hash passwords
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                //Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'Your account has been successfully registered and you can login');
                    redirect('users/login');
                } else {
                }
            } else {
                $this->view('users/register', $data);
            }
        } else {
            //Init data

            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_error' => '',
                'email_error' => '',
                'password_error' => '',
                'confirm_password_error' => '',
            ];

            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        //Check request method
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Process form

            //Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

            $data = [

                'email' =>  trim($_POST['email']),
                'password' =>  trim($_POST['password']),
                'email_error' => '',
                'password_error' => '',
                'error' => [],
            ];


            //Validate email address
            if (empty($data['email'])) {
                $data['email_error'] = 'Please enter your email address.';
                array_push($data['error'], $data['email_error']);
            }

            //Validate password
            if (empty($data['password'])) {
                $data['password_error'] = 'Please enter your password.';
                array_push($data['error'], $data['password_error']);
            }

            // Check for email address
            if ($this->userModel->findUserByEmail($data['email'])) {
                //User found
            } else {
                //User not found
                $data['email_error'] = 'No User found.';
                array_push($data['error'], $data['email_error']);
            }


            //Make sure  errors are empty
            if (empty($data['error'])) {
                //Validate
                //Check and set logged in use
                $loggedInUser = $this->userModel->login($data);
                if ($loggedInUser !== false) {
                    //Create session data
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_error'] = 'Incorrect password.';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }
        } else {
            //Init data

            $data = [
                'email' => '',
                'password' => '',
                'email_error' => '',
                'password_error' => '',
            ];

            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('users/login');
    }
}
