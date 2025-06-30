<?= $this->include("template/header") ?>
<h2><?= $title ?></h2>
<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title">
    </p>
    <p>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" cols="50" rows="10"></textarea>
    </p>
    <p>
        <label for="slug">Slug:</label><br>
        <input type="text" id="slug" name="slug">
    </p>
    <p>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category">
    </p>
    <p>
        <label for="created_at">Created At:</label><br>
        <input type="datetime-local" id="created_at" name="created_at" value="<?= date('Y-m-d\TH:i') ?>">
    </p>
    <p>
        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*">
    </p>
    <p><input type="submit" value="Submit" class="btn-back"></p>
</form>
<?= $this->include("template/footer") ?>
