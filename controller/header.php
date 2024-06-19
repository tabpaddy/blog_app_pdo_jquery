<?php

require_once './modal/headers.php';

class Header{
    private $headModal;

    public function __construct()
    {
        $this->headModal = new headers;
    }

    public function getUserAvatar() {
        if (isset($_SESSION['uid'])) {
            return $this->headModal->getUserAvatarById($_SESSION['uid']);
        }
        return null;
    }
}