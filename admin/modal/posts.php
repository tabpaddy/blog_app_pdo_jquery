<?php
require_once '../config/database.php';

class Posts {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }


    public function getPostsById($uid) {
        $this->db->query('  SELECT p.*, c.title as category_title 
                            FROM posts p 
                            JOIN categories c ON p.category_id = c.id 
                            WHERE p.author_id = :uid');
        $this->db->bind(':uid', $uid);
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : false;
    }

    public function getAllPosts() {
        $this->db->query('  SELECT p.*, c.title as category_title 
                            FROM posts p 
                            JOIN categories c ON p.category_id = c.id 
                            ');

        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : false;
    }

    public function getSinglePostsById($uid) {
        $this->db->query('  SELECT p.*, c.title as category_title 
                            FROM posts p 
                            JOIN categories c ON p.category_id = c.id 
                            WHERE p.id = :uid');
        $this->db->bind(':uid', $uid);
        $result = $this->db->single();

        return ($this->db->rowCount() > 0) ? $result : false;
    }

    public function isValidCategoryId($categoryId) {
        $this->db->query('SELECT id FROM categories WHERE id = :id');
        $this->db->bind(':id', $categoryId);
        $result = $this->db->single();
    
        return $result ? true : false;
    }
    

    public function userPostTitle($title){
        $this->db->query('SELECT * FROM posts WHERE title=:title');
        $this->db->bind(':title', $title);
        $result = $this->db->single();

        return ($this->db->rowCount() == 1) ? $result : false;
    }

    public function userPostbyId($id){
        $this->db->query('SELECT * FROM posts WHERE id=:id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        return ($this->db->rowCount() == 1) ? $result : false;
    }



    public function setAllIs_featuredToZero(){
        $this->db->query('UPDATE posts SET is_featured = 0');
        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function addPosts($data){
        $this->db->query('INSERT INTO posts (title, body, thumbnail, date_time, category_id, author_id, is_featured) VALUES (:title, :body, :thumbnail, :date_time, :category_id, :author_id, :is_featured)');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':thumbnail', $data['thumbnail_name']);
        $this->db->bind(':date_time', $data['date_time']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':author_id', $data['author_id']);
        $this->db->bind(':is_featured', $data['is_featured']);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }

    }

    public function deletePost($uid) {
        $this->db->query('DELETE FROM posts WHERE id=:id');

        // bindvalue
        $this->db->bind(':id', $uid);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function updatePost($data) {
        $this->db->query('UPDATE posts SET title=:title, body=:body, thumbnail=:thumbnail, category_id=:category_id, is_featured=:is_featured WHERE id=:id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':body', $data['body']);
        $this->db->bind(':thumbnail', $data['thumbnail_to_insert']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':is_featured', $data['is_featured']);

        // execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }

    }
}
?>
