<?php
require_once './config/database.php';

class pagesModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPostFeatured() {
        $this->db->query('
            SELECT p.*, c.title as category_title, u.firstname, u.lastname, u.avatar
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.author_id = u.user_id
            WHERE p.is_featured = 1
        ');
        $result = $this->db->single();

        return ($this->db->rowCount() > 0) ? $result : false;
    }

    public function getAllPost() {
        $this->db->query('
            SELECT p.*, c.title as category_title, u.firstname, u.lastname, u.avatar
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.author_id = u.user_id
            ORDER BY p.date_time DESC
        ');
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : [];
    }

    public function getCategory() {
        $this->db->query('SELECT * FROM categories ORDER BY id');
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : [];
    }

    public function searchPosts($searchTerm) {
        $this->db->query('
            SELECT p.*, c.title as category_title, u.firstname, u.lastname, u.avatar
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.author_id = u.user_id
            WHERE p.title LIKE :search
            ORDER BY p.date_time DESC
        ');
        $this->db->bind(':search', '%' . $searchTerm . '%');
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : [];
    }

    public function categoryPosts($id) {
        $this->db->query('
            SELECT p.*, c.title as category_title, u.firstname, u.lastname, u.avatar
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.author_id = u.user_id
            WHERE p.category_id = :id
            ORDER BY p.date_time DESC
        ');
        $this->db->bind(':id', $id);
        $result = $this->db->resultSet();

        return ($this->db->rowCount() > 0) ? $result : [];
    }

    public function getSinglePost($id) {
        $this->db->query('
            SELECT p.*, c.title as category_title, u.firstname, u.lastname, u.avatar
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.author_id = u.user_id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return ($this->db->rowCount() > 0) ? $result : false;
    }

    public function getAuthor($author_id) {
        $this->db->query('
            SELECT * FROM users WHERE user_id = :author_id
        ');
        $this->db->bind(':author_id', $author_id);
        $result = $this->db->single();
        return ($this->db->rowCount() > 0) ? $result : false;
    }

}
