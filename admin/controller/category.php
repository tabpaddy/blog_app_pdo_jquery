<?php
require_once '../config/database.php';
require_once '../modal/categorys.php';
require_once '../util/util.php';
require_once '../helper/session_helpers.php';

class Category {
    private $userCategory;
    private $util;

    public function __construct() {
        $this->userCategory = new Categorys();
        $this->util = new Util();
    }

    public function addCategory() {
        // Sanitize post data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'title' => $this->util->textInput($_POST['title']),
            'description' => $this->util->textInput($_POST['description'])
        ];

        // Validate inputs
        if (empty($data['title']) || empty($data['description'])) {
            echo json_encode(['status' => 'error', 'message' => 'Fill in all inputs', 'redirect' => 'add-category.php', 'data' => $data]);
            return;
        }

        // Check if category title has been inserted before
        if ($this->userCategory->categoryTitle($data['title'])) {
            echo json_encode(['status' => 'error', 'message' => 'Category name already taken', 'redirect' => 'add-category.php', 'data' => $data]);
            return;
        }

        // Add category
        if ($this->userCategory->addCategorys($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Category inserted successfully', 'redirect' => 'manage-category.php']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert category', 'redirect' => 'add-category.php', 'data' => $data]);
        }
    }

    public function updateCategory() {
        // Sanitize post data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'id' => $this->util->textInput($_POST['id']),
            'title' => $this->util->textInput($_POST['title']),
            'description' => $this->util->textInput($_POST['description'])
        ];

        // Validate inputs
        if (empty($data['title']) || empty($data['description'])) {
            echo json_encode(['status' => 'error', 'message' => 'Fill in all inputs']);
            return;
        }

        // Check if category title has been inserted before
        if ($this->userCategory->categoryTitle($data['title'])) {
            echo json_encode(['status' => 'error', 'message' => 'Category name already taken']);
            return;
        }

        // Add category
        if ($this->userCategory->editCategory($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Category inserted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert category']);
        }
    }

    public function getCategoryById(){
        $categorys = $this->userCategory->getCategory();
        $output = '';

        if($categorys){
            $output .= '<thead>
                    <tr>
                        <th>Title</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($categorys as $row) {
                $output .= '<tr>
                    <td>'. $row->title.'</td>
                    <td><a href="" data-id="'. $row->id .'" class="btn sm editLink">Edit</a></td>
                    <td><a href="" data-id="'. $row->id .'" class="btn sm danger deleteLink">Delete</a></td>
                </tr>';
            }

            $output .= '</tbody>';
            echo $output;
                
        } else {
            echo '<div class="alert__message error">No categories found</div>';
        }
    }

    public function deleteById(){
        $id = $_GET['id'];
        if($this->userCategory->setPostToCategory($id)){
       if($this->userCategory->deleteCategory($id)) {
        echo json_encode(['status' => 'success', 'message' => 'Deleted successfully', 'redirect' => 'manage-category.php']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error occurred']);
    }
}
    }

    

   
}

$init = new Category();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $init->addCategory();
    }elseif (isset($_POST['update'])) {
        $init->updateCategory();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['cate'])) {
        $init->getCategoryById();
    }elseif (isset($_GET['deleteCategory'])) {
        $init->deleteById();
    }
}else{
    redirect('admin/index.php');
}
?>
