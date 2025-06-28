<?= $this->include("template/header") ?>
<form method="get" action="<?= base_url('/article') ?>" style="margin-bottom:20px;">
    <input type="text" name="q" value="<?= isset(
        $search
    ) ? esc($search) : '' ?>" placeholder="Search articles..." />
    <select name="category">
        <option value="">All Categories</option>
        <?php if (!empty($categories)): foreach ($categories as $cat): ?>
            <option value="<?= esc($cat['category']) ?>" <?= (isset($category) && $category == $cat['category']) ? 'selected' : '' ?>><?= esc($cat['category']) ?></option>
        <?php endforeach; endif; ?>
    </select>
    <select name="sort">
        <option value="desc" <?= (isset($sort) && $sort == 'desc') ? 'selected' : '' ?>>Latest</option>
        <option value="asc" <?= (isset($sort) && $sort == 'asc') ? 'selected' : '' ?>>Oldest</option>
    </select>
    <button type="submit">Search</button>
</form>
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
<?= $this->include("template/footer") ?>