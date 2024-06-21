<?php

require_once '../config/database.php';

class headers{
    private $head;

    public function __construct()
    {
        $this->head = new Database();
    }

    public function getUserAvatarById($id) {
        $this->head->query('SELECT avatar FROM users WHERE user_id = :id');
        $this->head->bind(':id', $id);
        return $this->head->single();
    }
}