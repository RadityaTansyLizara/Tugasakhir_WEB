<?= $this->include("template/header") ?>
<h2><?= $title ?></h2>
<form action="" method="post">
    <p>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?= $data["title"] ?>">
    </p>
    <p>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" cols="50" rows="10"><?= $data["content"] ?></textarea>
    </p>
    <p>
        <label for="slug">Slug:</label><br>
        <input type="text" id="slug" name="slug" value="<?= $data["slug"] ?>">
    </p>
    <p>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" value="<?= $data["category"] ?>">
    </p>
    <p>
        <label for="created_at">Created At:</label><br>
        <input type="datetime-local" id="created_at" name="created_at" value="<?= date('Y-m-d\TH:i', strtotime($data['created_at'])) ?>">
    </p>
    <p><input type="submit" value="Submit" class="btn-back"></p>
</form>
<?= $this->include("template/footer") ?>
