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
        $this->db->bind(':is_admin', $data['userrole']);

        return $this->db->execute();
    }

    public function update($data) {
        $this->db->query('UPDATE users SET firstname=:firstname, lastname=:lastname, is_admin=:is_admin WHERE user_id=:id');

        $this->db->bind(':id', $data['user_id']);
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':is_admin', $data['userrole']);

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

    public function getPostThumbnailsByUserId($id){
        $this->db->query('SELECT thumbnail FROM posts WHERE author_id=:id');
        $this->db->bind(':id', $id);

        $row = $this->db->resultSet();

        // check row
        if($this->db->rowCount() > 0){
            return $row;
        }else{
            return false;
        }
    }

    public function deleteUserPosts($id) {
        $this->db->query('DELETE FROM posts WHERE author_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUserPostById($id){
        $this->db->query('DELETE FROM posts WHERE author_id=:id');

        // bindvalue
        $this->db->bind(':id', $id);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE user_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    
    

    public function getAlluser($id){
        $this->db->query('SELECT * FROM users WHERE NOT user_id =:id');
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function getSingleUserById($id){
        $this->db->query('SELECT * FROM users WHERE user_id =:id');
        $this->db->bind(':id', $id);
        return $this->db->single();

    }
}

