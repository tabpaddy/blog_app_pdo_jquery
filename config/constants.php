<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ROOT_URL')) {
    define('ROOT_URL', 'http://localhost:8080/blog_app_pdo/');
}