<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './modal/categorys.php';


class EditCategory{
    private $CategoryEdit;

    public function __construct()
    {
        $this->CategoryEdit = new Categorys;
    }

    public function getCategoryWithId($id){
        
        return $this->CategoryEdit->getSingleCategory($id);
        
    }
}

$init = new EditCategory;
$category = null;

if(isset($_GET['id'])) {
    $category = $init->getCategoryWithId($_GET['id']);

    // var_dump($category);
    if(!$category){
        echo "category not found";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog Website</title>
<!-- Custom CSS -->
<link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
<!-- Icon Scout -->
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<section class="form__section">
    <div class="container form__selection-container">
        <h2>Edit Category</h2>
<?php if($category): ?>
        <div class="showAlert"></div>
            <form id="edit-category-form" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($category->id) ?>">
                <input type="text" name="title" id="title" placeholder="Title" value="<?= htmlspecialchars($category->title) ?>">
                <textarea name="description" id="description" cols="30" rows="4" placeholder="Description"><?= htmlspecialchars($category->description) ?></textarea>
                <button type="submit" class="btn" name="submit" id="edit-category-btn">Update Category</button>
            </form>
        <?php else: ?>
            <div class="alert__message error"><p>Category not found</p></div>
         <?php endif ?>   
    </div>
</section>

<script src="../js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('submit', '#edit-category-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("update", 1);

        $.ajax({
            url: '../admin/controller/category.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('.showAlert').html('<div class="alert__message success">' + response.message + '</div>');
                    setTimeout(function() {
                        window.location.href = 'manage-category.php';
                    }, 1000);
                } else {
                    $('.showAlert').html('<div class="alert__message error">' + response.message + '</div>');

                     // Clear error messages after 2 seconds
                     setTimeout(function(){
                        $('.showAlert').empty();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log("Error occurred:");
                console.log(xhr.responseText);
                console.log(status + ': ' + error);
            }
        });
    });
</script>
</body>
</html>
