<?php
require_once './modal/posts.php';

class MainPage {
    private $pageModel;

    public function __construct() {
        $this->pageModel = new pagesModel();
    }

    public function getAllCategories() {
        return $this->pageModel->getCategory();
    }

    public function getAllPost() {
        return $this->pageModel->getAllPost();
    }

    public function getFeaturedPost() {
        return $this->pageModel->getPostFeatured();
    }

    public function searchPosts($searchTerm) {
        return $this->pageModel->searchPosts($searchTerm);
    }

    public function categoryPosts($categoryId) {
        return $this->pageModel->categoryPosts($categoryId);
    }

    public function getSinglePost($id) {
        return $this->pageModel->getSinglePost($id);
    }

    public function getAuthor($author_id) {
        return $this->pageModel->getAuthor($author_id);
    }
}
