<?= $this->include("template/header") ?>
<article class="entry">
<h2><?= $article["title"] ?></h2>
<img src="<?= base_url("/image/" . $article["image"]) ?>" alt="<?= $article[
    "slug"
] ?>">
<p><?= $article["content"] ?></p>
</article>
<?= $this->include("template/footer") ?>
