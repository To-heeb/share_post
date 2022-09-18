<?php

//Simple page redirect 
function redirect($page)
{
    header("Location:" . BASE_URL . '/' . $page);
}
