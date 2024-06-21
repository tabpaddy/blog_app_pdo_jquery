<?php

require_once '../modal/posts.php';
require_once '../util/util.php';
require_once '../helper/session_helpers.php';

class Post {
    private $userPost;
    private $util;

    public function __construct() {
        $this->userPost = new Posts();
        $this->util = new Util();
    }

    public function addUserPosts(){
        if(isset($_SESSION['uid'])){
            $author_id = $_SESSION['uid']; 
         }
        // sanitize post

        // Sanitize post
        $_POST = filter_input_array(INPUT_POST, [
            'user_id' => FILTER_SANITIZE_NUMBER_INT,
            'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'category' => FILTER_SANITIZE_NUMBER_INT,
            'body' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'is_featured' => FILTER_SANITIZE_NUMBER_INT
        ]);

        $data = [
            'author_id' => $author_id,
            'title' => $this->util->textInput($_POST['title']),
            'category_id' => $this->util->textInput($_POST['category'],),
            'body' => $this->util->textInput($_POST['body']),
            'is_featured' => $this->util->textInput($_POST['is_featured']),
            'thumbnail' => $_FILES['thumbnail'],
        ];

          // Set is_featured to 0 if unchecked
        $data['is_featured'] = $data['is_featured'] == 1 ? 1 : 0;

        // validate input
        if(!$data['title']){
            echo json_encode(['status' => 'error', 'message' => 'Inputs a title', 'redirect' => 'add-post.php', 'data' => $data]);
            return;
        }elseif($this->userPost->userPostTitle($data['title'])){
            echo json_encode(['status' => 'error', 'message' => 'Title already Taken', 'redirect' => 'add-post.php', 'data' => $data]);
            return;
        }elseif(!$data['category_id']){
            echo json_encode(['status' => 'error', 'message' => 'Select a category', 'redirect' => 'add-post.php', 'data' => $data]);
            return;
        }elseif(!$data['body']){
            echo json_encode(['status' => 'error', 'message' => 'Enter post body', 'redirect' => 'add-post.php', 'data' => $data]);
            return;
        }elseif(!$data['thumbnail']['name']){
            echo json_encode(['status' => 'error', 'message' => 'Choose post thumbnail', 'redirect' => 'add-post.php', 'data' => $data]);
            return;
        }else{
            // handle file upload

            $time = time();
            $fileName = $time.basename($data['thumbnail']['name']);
            $targetDir = "../../image/";
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check file type
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $validExtensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG, and GIF files are allowed', 'redirect' => 'add-post.php', 'data' => $data]);
                return;
            }

            // Check if file already exists
            if (file_exists($targetFile)) {
                echo json_encode(['status' => 'error', 'message' => 'File already exists', 'redirect' => 'add-post.php', 'data' => $data]);
                return;
            }

            // Check file size (limit to 2MB)
            if ($data['thumbnail']['size'] > 2000000) {
                echo json_encode(['status' => 'error', 'message' => 'File size should be less than 2MB', 'redirect' => 'add-post.php', 'data' => $data]);
                return;
            }elseif (move_uploaded_file($data['thumbnail']['tmp_name'], $targetFile)) {
                $data['thumbnail_name'] = $fileName;
                $data['date_time'] = date('Y-m-d H:i:s'); // Set the current datetime
                if($data['is_featured'] == 1){
                    $this->userPost->setAllIs_featuredToZero();
                }
                if ($this->userPost->addPosts($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Registration successful', 'redirect' => 'index.php']);
                    return;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Registration failed', 'redirect' => 'add-post.php', 'data' => $data]);
                    return;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload thumbnail', 'redirect' => 'add-post.php', 'data' => $data]);
                    return;
            }
        }
    }

    public function updateUserPosts() {
        // Sanitize post
        $_POST = filter_input_array(INPUT_POST, [
            'id' => FILTER_SANITIZE_NUMBER_INT,
            'previous_thumbnail_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'category' => FILTER_SANITIZE_NUMBER_INT,
            'body' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'is_featured' => FILTER_SANITIZE_NUMBER_INT
        ]);
    
        $data = [
            'id' => $this->util->textInput($_POST['id']),
            'previous_thumbnail_name' => $this->util->textInput($_POST['previous_thumbnail_name']),
            'title' => $this->util->textInput($_POST['title']),
            'category_id' => $this->util->textInput($_POST['category']),
            'body' => $this->util->textInput($_POST['body']),
            'is_featured' => $this->util->textInput($_POST['is_featured']),
            'thumbnail' => $_FILES['thumbnail']
        ];
    
        // Set is_featured to 0 if unchecked
        $data['is_featured'] = $data['is_featured'] == 1 ? 1 : 0;
    
        // Initialize thumbnail_to_insert
        $data['thumbnail_to_insert'] = $data['previous_thumbnail_name'];
    
        // Validate input
        if (!$data['title']) {
            echo json_encode(['status' => 'error', 'message' => 'Could not update post. Invalid form data on edit post page.']);
            return;
        } elseif (!$data['category_id']) {
            echo json_encode(['status' => 'error', 'message' => 'Could not update post. Invalid form data on edit post page.']);
            return;
        } elseif (!$data['body']) {
            echo json_encode(['status' => 'error', 'message' => 'Could not update post. Invalid form data on edit post page.']);
            return;
        } else {
            // Validate category_id
            if (!$this->userPost->isValidCategoryId($data['category_id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid category ID.']);
                return;
            }
    
            if ($data['thumbnail']['name']) {
                $previous_filename_path = '../../image/' . $data['previous_thumbnail_name'];
                if (file_exists($previous_filename_path)) {
                    unlink($previous_filename_path);
                }
    
                // Handle file upload
                $time = time();
                $fileName = $time . basename($data['thumbnail']['name']);
                $targetDir = "../../image/";
                $targetFile = $targetDir . $fileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
                // Check file type
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($imageFileType, $validExtensions)) {
                    echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, PNG, and GIF files are allowed']);
                    return;
                }
    
                // Check if file already exists
                if (file_exists($targetFile)) {
                    echo json_encode(['status' => 'error', 'message' => 'File already exists']);
                    return;
                }
    
                // Check file size (limit to 2MB)
                if ($data['thumbnail']['size'] > 2000000) {
                    echo json_encode(['status' => 'error', 'message' => 'File size should be less than 2MB']);
                    return;
                } elseif (move_uploaded_file($data['thumbnail']['tmp_name'], $targetFile)) {
                    $data['thumbnail_name'] = $fileName;
                    $data['thumbnail_to_insert'] = $data['thumbnail_name'];
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to upload thumbnail']);
                    return;
                }
            }
    
            if ($data['is_featured'] == 1) {
                $this->userPost->setAllIs_featuredToZero();
            }
    
            if ($this->userPost->updatePost($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Post updated successfully', 'redirect' => 'index.php']);
                return;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update post']);
                return;
            }
        }
    }
    

    public function getUserPosts() {
        if (isset($_SESSION['uid'])) {
            $post = $this->userPost->getPostsById($_SESSION['uid']);
            $output = '';
            // var_dump($post);
            if ($post) {
                $output .= '<thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>';

                foreach ($post as $row) {
                    $output .= '<tr>
                        <td>' . htmlspecialchars($row->title) . '</td>
                        <td>' . htmlspecialchars($row->category_title) . '</td>
                        <td><a href="" data-id="' . htmlspecialchars($row->id) . '" class="btn sm editPost">Edit</a></td>
                        <td><a href="" data-id="' . htmlspecialchars($row->id) . '" class="btn sm danger deletePost">Delete</a></td>
                    </tr>';
                }

                $output .= '</tbody>';
                echo $output;
            } else {
                echo '<div class="alert__message error">No post found</div>';
            }
        }
    }

    public function deletePostById(){
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        if($thumbnail = $this->userPost->userPostbyId($id)){
            $thumbnail_name = $thumbnail->thumbnail;
            $thumbnail_path = '../../image/' . $thumbnail_name;
            if($thumbnail_path){
                unlink($thumbnail_path);
            }

        if($this->userPost->deletePost($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Deleted successfully', 'redirect' => 'index.php']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error occurred']);
            return;
        }
        }
    }
}

$init = new Post();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle POST requests
    if(isset($_POST['add'])){
        $init->addUserPosts();
    }elseif(isset($_POST['update'])){
        $init->updateUserPosts();
    }else{
        redirect('admin/index.php');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['read'])) {
    $init->getUserPosts();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['deletePost'])) {
    $init->deletePostById();
}else{
    redirect('admin/index.php');
}
?>


