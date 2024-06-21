<?php
require_once '../config/database.php';

class Categorys {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCategory(){
        $this->db->query('SELECT * FROM categories ORDER BY id');
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : false; 
    }

    public function getSingleCategory($id){
        $this->db->query('SELECT * FROM categories WHERE id=:id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        return ($this->db->rowCount() == 1) ? $result : false; 
    }

    public function categoryTitle($title) {
        $this->db->query('SELECT * FROM categories WHERE title = :title');
        $this->db->bind(':title', $title);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function addCategorys($data) {
        // Prepare the SQL query with a LIMIT clause
        $this->db->query('INSERT INTO categories (title, description) VALUES (:title, :description)');
    
        // Bind the data to the query
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
    
        // Execute the query and check if it was successful
        return $this->db->execute();
    }
    

    public function deleteCategory($id){
        $this->db->query('DELETE FROM categories WHERE id=:id');
        // bindvalue
        $this->db->bind(':id', $id);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function setPostToCategory($id){
        $this->db->query('UPDATE posts SET category_id = 5 WHERE category_id=:id');
        // bindvalue
        $this->db->bind(':id', $id);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function editCategory($data){
        $this->db->query('UPDATE categories SET title=:title, description=:description WHERE id=:id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);

        // execute
        $this->db->execute();

        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }
}