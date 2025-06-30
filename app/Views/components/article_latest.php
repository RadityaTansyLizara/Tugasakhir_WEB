<div class="widget-box">
  <h3 class="title">Latest Article<?= $category ? " - " . $category : "" ?></h3>
  <ul>
    <?php if (!empty($article)): ?>
        <?php foreach ($article as $row): ?>
        <li>
          <a href="<?= base_url("/article/" . $row["slug"]) ?>">
            <?= $row["title"] ?>
          </a>
          <br>
          <small>Posted in: <?= date(
              "d M Y H:i",
              strtotime($row["created_at"])
          ) ?></small>
          <br>
          <small>Category: <?= $row["category"] ?></small>
        </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>There are no articles in this category.</p>
    <?php endif; ?>
  </ul>
</div>
