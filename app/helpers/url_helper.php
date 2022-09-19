<?php

//Simple page redirect 
function redirect($page)
{
    header("Location:" . BASE_URL . '/' . $page);
}

//template loader
function template($page)
{
    require_once APPROOT . '/views/' . $page . '.php';
}
