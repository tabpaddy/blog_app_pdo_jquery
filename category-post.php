<?php
include('./partials/header.php');
require_once './controller/post.php';

$init = new MainPage;
$categories = $init->getAllCategories();

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $posts = $init->categoryPosts($id);
} else {
    header('location: ' . ROOT_URL . 'index.php');
    die();
}
?>

<header class="category__title">
    <h2>Category: <?= htmlspecialchars($categories[array_search($id, array_column($categories, 'id'))]->title) ?></h2>
</header>
<!-- end of category title -->

<section class="post">
    <div class="container post__container">
        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($post->thumbnail) ?>" alt="Post Thumbnail">
                    </div>
                    <div class="post__info">
                        <h3 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= htmlspecialchars($post->id) ?>"><?= htmlspecialchars($post->title) ?></a></h3>
                        <p class="post__body">
                            <?= substr(htmlspecialchars($post->body), 0, 150) ?>...
                        </p>
                        <div class="post__author">
                            <div class="post__author-avatar">
                                <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($post->avatar) ?>" alt="Author Avatar">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= htmlspecialchars("{$post->firstname} {$post->lastname}") ?></h5>
                                <small><?= date("M, d, Y - H:i", strtotime($post->date_time)) ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert__message error">No posts found in this category</div>
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
