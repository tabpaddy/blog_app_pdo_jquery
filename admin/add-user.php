<?php
require_once('./partial/header.php');



?>
    
    <section class="form__section">
    <div class="container form__selection-container">
        <h2>Add User</h2>
        <div class="showAlert"></div>
        <form id="add-user-form" enctype="multipart/form-data" method="post">
            <input type="text" name="firstname" placeholder="First Name" value="">
            <input type="text" name="lastname" placeholder="Last Name" value="">
            <input type="text" name="username" placeholder="Username" value="">
            <input type="email" name="email" placeholder="Email" value="">
            <input type="password" name="password" placeholder="Create Password" value="">
            <input type="password" name="cpassword" placeholder="Confirm Password" value="">
            <select name="userrole">
                <option value="0">Author</option>
                <option value="1">Admin</option>
            </select>
            <div class="form__control">
                <label for="avatar">User Avatar</label>
                <input type="file" id="avatar" name="avatar" value="">
            </div>
            <button type="submit" class="btn" name="submit" id="add-user-btn">Add User</button>
        </form>
    </div>
</section>



<?php
require_once '../partials/footer.php';
?>