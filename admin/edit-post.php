<?php

require_once('./partial/header.php');
require_once './modal/posts.php';

require_once './controller/categoryController.php';

$init = new CategoryController();
$categories = $init->getAllCategories();

class EditPost{
    private $edit;

    public function __construct()
    {
       $this->edit = new Posts; 
    }

    public function getSinglePostsById($id){
        return $this->edit->getSinglePostsById($id);
    }

    public function getcategoryTitlewithid(){

    }
}

$init = new EditPost;
$postData = null;

if(isset($_GET['id'])) {
    $postData = $init->getSinglePostsById($_GET['id']);

    // var_dump($postData);
    if(!$postData){
        echo "Post not found";
        exit;
    }
}




?>

    
    <section class="form__section">
    <div class="container form__selection-container">
        <h2>Edit Post</h2>
        <div class="showAlert"></div>
        <form id="edit-post-form" enctype="multipart/form-data" method="post">
            <input type="hidden" name="id" value="<?= $postData->id ?>">
            <input type="hidden" name="previous_thumbnail_name" value="<?= $postData->thumbnail ?>">
            <input type="text" name="title" id="" placeholder="Title" value="<?= $postData->title ?>">
            <select name="category">
            <?php foreach($categories as $category): ?>
                <option value="<?= htmlspecialchars($category -> id) ?>" ><?= htmlspecialchars($category->title) ?></option>
                <?php endforeach; ?>
            </select>
            <textarea id=""  rows="10" placeholder="Body" name="body"><?= $postData->body ?></textarea>
            <?php if(isset($_SESSION['user-is-admin'])): ?>
            <div class="form__control Featured">
                <input type="checkbox" id="is_featured" value="1" name="is_featured" <?= ($postData->is_featured) ? 'checked' : '' ?>>
                <label for="is_featured">Featured</label>
            </div>
            <?php endif; ?>
            <div class="form__control">
                <label for="thumbnail">Change Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" >
            </div>
            <button type="submit" class="btn" name="submit" id="update-post-btn">Update Post</button>
        </form>
    </div>
</section>



<?php
require_once '../partials/footer.php';
?>