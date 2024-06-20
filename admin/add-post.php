<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './partial/header.php';
require_once './controller/categoryController.php';

$init = new CategoryController();
$categories = $init->getAllCategories();


$title = '';
$category_id = '';
$body = '';
$thumbnail = '';
$is_featured = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $category_id = htmlspecialchars($_POST['category']);
    $body = htmlspecialchars($_POST['body']);
    $thumbnail = htmlspecialchars($_FILES['thumbnail']['name']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
}
?>

<section class="form__section">
    <div class="container form__selection-container">
        <h2>Add Post</h2>
        <div class="showAlert"></div>
        <form id="add-post" enctype="multipart/form-data" method="post">
            <input type="text" name="title" id="" placeholder="Title" value="<?= $title ?>">
            <select name="category">
                <?php foreach($categories as $category): ?>
                <option value="<?= htmlspecialchars($category -> id) ?>" <?= ($category->id !== $category_id) ? '' : 'selected' ?>><?= htmlspecialchars($category->title) ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="body" id="" rows="10" placeholder="Body"><?= $body ?></textarea>
            <?php if(isset($_SESSION['user-is-admin'])): ?>
            <div class="form__control Featured">
                <input type="checkbox" id="is_featured" value="1" name="is_featured" <?= ($is_featured) ? 'checked' : '' ?>>
                <label for="is_featured">Featured</label>
            </div>
            <?php endif; ?>
            <div class="form__control">
                <label for="thumbnail">Add Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" value="<?= $thumbnail ?>">
            </div>
            <button type="submit" class="btn" name="submit" id="add-pots-btn">Add Post</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>
