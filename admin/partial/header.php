<?php
require_once '../config/database.php';
require_once './controller/header.php';


// Initialize User controller
$userController = new header();
$avatar = null;

if (isset($_SESSION['uid'])) {
    $avatar = $userController->getUserAvatar();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website PDO</title>
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
    <!-- Start of Navbar -->
    <nav>
        <div class="container nav__container">
            <a href="<?= ROOT_URL ?>index.php" class="nav__logo">PRAISE</a>
            <ul class="nav__items">
                <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
                <li><a href="<?= ROOT_URL ?>about.php">About</a></li>
                <li><a href="<?= ROOT_URL ?>service.php">Service</a></li>
                <li><a href="<?= ROOT_URL ?>contact.php">Contact</a></li>
                <?php if (isset($_SESSION['uid'])) : ?>
                <li class="nav__profile">
                    <div class="avatar">
                        <img src="<?= ROOT_URL . 'image/' . $avatar->avatar ?>" alt="profile_img">
                    </div>
                    <ul>
                        <li><a href="<?= ROOT_URL ?>admin/">Dashboard</a></li>
                        <li><a href="<?= ROOT_URL ?>logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php else : ?>
                <li><a href="<?= ROOT_URL ?>users_ath/signing.php">Sign in</a></li>
                <?php endif; ?>
            </ul>
            <button id="open_nav_btn"><i class="uil uil-bars"></i></button>
            <button id="close_nav_btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>
    <!-- End of Navbar -->
</body>
</html>
