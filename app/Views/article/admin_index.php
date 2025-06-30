<?= $this->include("template/header") ?>

<div id="container">
    <a href="<?= base_url('/admin/article/add') ?>" class="btn-read-more">➕ Add Article</a>

    <div id="main">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Function</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($article): ?>
                        <?php foreach ($article as $row): ?>
                            <tr>
                                <td><?= esc($row["id"]) ?></td>
                                <td>
                                    <b class="title-text"><?= esc($row["title"]) ?></b>
                                    <p class="content-preview"><?= esc(substr(strip_tags($row["content"]), 0, 50)) ?>...</p>
                                </td>
                                <td class="slug-column"><?= esc($row["slug"]) ?></td>
                                <td><?= esc($row["category"] ?? 'N/A') ?></td>
                                <td><?= date('d M Y, H:i', strtotime($row["created_at"] ?? 'now')) ?></td>
                                <td><?= esc($row["status"] ?? 'Draft') ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-read-more" href="<?= base_url("/admin/article/edit/" . $row["id"]) ?>">✏️ Edit</a>
                                        <a class="btn btn-back" onclick="return confirm('Are you sure?');" href="<?= base_url("/admin/article/delete/" . $row["id"]) ?>">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-color);">No articles available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include("template/footer") ?>
