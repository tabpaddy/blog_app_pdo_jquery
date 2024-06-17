<?php
include('./partials/header.php');
require_once './controller/post.php';

$init = new MainPage;

if(isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    $post = $init->getSinglePost($id);

    if ($post) {
        $author_id = $post->author_id;
        $author = $init->getAuthor($author_id);
    } else {
        header('Location: ' . ROOT_URL . 'index.php');
        exit();
    }
} else {
    header('Location: ' . ROOT_URL . 'index.php');
    exit();
}
?>

<section class="singlepost">
    <div class="container singlepost__container">
        <h2><?= htmlspecialchars($post->title) ?></h2>
        <div class="post__author">
            <div class="post__author-avatar">
                <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($author->avatar) ?>" alt="Author Avatar">
            </div>
            <div class="post__author-info">
                <h5>By: <?= htmlspecialchars("{$author->firstname} {$author->lastname}") ?></h5>
                <small><?= date("M, d, Y - H:i", strtotime($post->date_time)) ?></small>
            </div>
        </div>
        <div class="singlepost__thumbnail">
            <img src="<?= ROOT_URL ?>image/<?= htmlspecialchars($post->thumbnail) ?>" alt="Post Thumbnail">
        </div>
        <p>
            <?= nl2br(htmlspecialchars($post->body)) ?>
        </p>
    </div>
</section>

<!-- end of single post -->

<?php include('./partials/footer.php'); ?>
