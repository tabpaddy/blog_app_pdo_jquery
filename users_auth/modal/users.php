<?php
require_once '../config/database.php';

class Users {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES (:firstname, :lastname, :username, :email, :password, :photo, :is_admin)');

        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':photo', $data['photo_name']);
        $this->db->bind(':is_admin', $data['is_admin']);

        return $this->db->execute();
    }

    public function findUserByEmailOrUsername($email, $username) {
        $this->db->query('SELECT * FROM users WHERE email = :email OR username = :username');
        $this->db->bind(':email', $email);
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // check row
        if($this->db->rowCount() > 0){
            return $row;
        }else{
            return false;
        }
    }

    public function login($username_email, $password){
        $row = $this->findUserByEmailOrUsername($username_email, $username_email);

        if($row == false){
            return false;
        }else{
            // hashed password
            $hashedPassword = $row->password;


            if(password_verify($password, $hashedPassword)){

                return $row;
            }else{

                return false;
            }
        }
    }

    public function getUserAvatarById($id) {
        $this->db->query('SELECT avatar FROM users WHERE user_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}

