<?php
require_once './modal/categorys.php';

class CategoryController {
    private $categoryModal;

    public function __construct()
    {
        $this->categoryModal = new Categorys;
    }

    public function getAllCategories() {
        return $this->categoryModal->getCategory();
    }
}

// $init = new CategoryController();
// $categories = $init->getAllCategories();

