<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?= esc($title); ?></title>
  </head>
  <body>
    <?= $this->include('template/header'); ?>

    <main>
      <h1><?= esc($title); ?></h1>
      <hr>
      <p><?= esc($content); ?></p>
    </main>

    <?= $this->include('template/footer'); ?>
  </body>
</html>
