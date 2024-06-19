<?php
if(!isset($_SESSION)){
    session_start();
}

function flash($name = '', $message = '', $class = 'alert__message error'){
    if(!empty ($name)){
        if(!empty ($message) && empty($_SESSION[$name])){
            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        }else if(empty ($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : $class;
            echo '<div class="'.$class.'" ><p>'.$_SESSION[$name].'</p></div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

function redirect($location){
    header("location: ".ROOT_URL.$location);
    exit();
}