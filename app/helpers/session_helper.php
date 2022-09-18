<?php
session_start();


/** 
 * Flash message helper
 * examples: flash('register_success', 'You're now registered successfully', 'alert alert-danger')
 * displays in view: echo flash('register_success');
 */
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            if (!empty($_SESSION[$name . '_message'])) {
                unset($_SESSION[$name . '_message']);
            }

            $_SESSION[$name] = $name;
            $_SESSION[$name . '_class'] = $class;
            $_SESSION[$name . '_message'] = $message;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            $message = !empty($_SESSION[$name . '_message']) ? $_SESSION[$name . '_message'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $message . '</div>';

            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
            unset($_SESSION[$name . '_message']);
        }
    }
}

function is_logged_in()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    }

    return false;
}
