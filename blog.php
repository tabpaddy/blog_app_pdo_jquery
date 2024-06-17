<?php
include('./partials/header.php');
require_once './controller/post.php';

$init = new MainPage;
$categories = $init->getAllCategories();
$posts = $init->getAllPost();
?>

<section class="search__bar">
    <form action="<?= ROOT_URL ?>search.php" method="get" class="container search__bar-container">
        <div>
            <i class="uil uil-search"></i>
            <input type="search" name="search" placeholder="search">
        </div>
        <button type="submit" class="btn" name="submit">Go</button>
    </form>
</section>
<!-- end of Search -->
    
 <!-- show all posts -->
<section class="post <?= $featured ? '' : 'section__extra-margin' ?>">
    <div class="container post__container">
        <?php if ($posts): ?>
            <?php foreach ($posts as $row): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($row->thumbnail) ?>" alt="Post Thumbnail">
                    </div>
                    <div class="post__info">
                        <a href="<?= ROOT_URL ?>category-post.php?id=<?= htmlspecialchars($row->category_id) ?>" class="category__button"><?= htmlspecialchars($row->category_title) ?></a>
                        <h3 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= htmlspecialchars($row->id) ?>"><?= htmlspecialchars($row->title) ?></a></h3>
                        <p class="post__body">
                            <?= substr(htmlspecialchars($row->body), 0, 190) ?>...
                        </p>
                        <div class="post__author">
                            <div class="post__author-avatar">
                                <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($row->avatar) ?>" alt="Author Avatar">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= htmlspecialchars("{$row->firstname} {$row->lastname}") ?></h5>
                                <small><?= date("M, d, Y - H:i", strtotime($row->date_time)) ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert__message error"><?= "No posts found" ?></div>
        <?php endif; ?>
    </div>
</section>
<!-- end of posts section -->

<!-- category buttons -->
<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php foreach ($categories as $category): ?>
            <a href="<?= ROOT_URL ?>category-post.php?id=<?= htmlspecialchars($category->id) ?>" class="category__button"><?= htmlspecialchars($category->title) ?></a>
        <?php endforeach; ?>
    </div>
</section>
<!-- end of category buttons -->

<?php include('./partials/footer.php'); ?>
