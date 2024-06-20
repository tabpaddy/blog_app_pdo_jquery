<?php
require_once('./partial/header.php');
require_once './modal/users.php';

class EditUser {
    private $user;

    public function __construct() {
        $this->user = new Users;
    }

    public function getSingleUser($id) {
        return $this->user->getSingleUserById($id);
    }
}

$inits = new EditUser;
$userData = null;

if(isset($_GET['id'])){
    $userData = $inits->getSingleUser($_GET['id']);

    if(!$userData){
        echo "user not found";
        exit;
    }
}
?>

<section class="form__section">
    <div class="container form__selection-container">
        <h2>Edit User</h2>
        <div class="showAlert"></div>
        <form id="edit-user-form" method="post">
            <input type="hidden" value="<?=$userData->user_id?>" name="user_id">
            <input type="text" name="firstname" id="" placeholder="First Name" value="<?=$userData->firstname?>">
            <input type="text" name="lastname" id="" placeholder="Last Name" value="<?=$userData->lastname?>">
            <select name="userrole" id="">
                <option value="0" <?= $userData->is_admin == 0 ? 'selected' : '' ?>>Author</option>
                <option value="1" <?= $userData->is_admin == 1 ? 'selected' : '' ?>>Admin</option>
            </select>
            <button type="submit" class="btn" name="submit">Update User</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>
