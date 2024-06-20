<?php
include('./partial/header.php');
require_once './helper/session_helpers.php';




?>

    
<section class="form__section">
    <div class="container form__selection-container">
        <h2>ADD Category</h2>
        <div class="showAlert"></div>
        <form id="add-category-form" class="add-category-form">
            <input type="text" name="title" placeholder="Title" value="">
            <textarea name="description" cols="30" rows="4" placeholder="Description"></textarea>
            <button type="submit" class="btn" id="add-category-btn">Add Category</button>
        </form>
    </div>
</section>



<?php
include '../partials/footer.php';
?>