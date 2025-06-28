<div id="article-list">
<?php if ($article):
    foreach ($article as $row): ?>
<article class="entry">
    <h2><a href="<?= base_url("/article/" . $row["slug"]) ?>"><?= $row["title"] ?></a></h2>
    <img src="<?= base_url("/image/" . $row["image"]) ?>" alt="<?= $row["slug"] ?>">
    <p><?= substr($row["content"], 0, 200) ?>...</p>
    <a href="<?= base_url("/article/" . $row["slug"]) ?>" class="btn-read-more">Read More</a>
</article>
<hr class="divider" />
<?php endforeach;
else: ?>
<article class="entry">
    <h2>No articles available.</h2>
</article>
<?php endif; ?>
</div> 