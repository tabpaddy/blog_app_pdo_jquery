<?php
require_once '../config/database.php';
require_once('./partial/header.php');
require_once 'helper/session_helpers.php';


?>

<section class="dashboard">
<div class="showAlert"></div>
    <div class="container dashboard__container">
        <button id="show__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-right-b"></i></button>
        <button id="hide__sidebar-btn" class="sidebar__toggle"><i class="uil uil-angle-left-b"></i></button>
        <aside>
            <ul>
                <li><a href="add-post.php"><i class="uil uil-pen"></i>
                <h5>Add Post</h5>
                </a></li>
                <li><a href="index.php"><i class="uil uil-postcard"></i>
                <h5>Manage Post</h5>
                </a></li>
                <?php if(isset($_SESSION['user-is-admin'])): ?>
                <li><a href="add-user.php"><i class="uil uil-user-plus"></i>
                <h5>Add User</h5>
                </a></li>
                <li><a href="manage-user.php"><i class="uil uil-users-alt"></i>
                <h5>Manage User</h5>
                </a></li>
                <li><a href="add-category.php"><i class="uil uil-edit"></i>
                <h5>Add Category</h5>
                </a></li>
                <li><a href="manage-category.php" class="active"><i class="uil uil-list-ul"></i>
                <h5>Manage Category</h5>
                </a></li>
                <?php endif ?>
            </ul>
        </aside>

        <main>
            <h2>Manage Categories</h2>
            
            <table id="categoryData">
    
            </table>
        
                
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>