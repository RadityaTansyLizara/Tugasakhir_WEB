<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title> <?= $title; ?> </title>
    <link rel="stylesheet" href="
	<?= base_url('/style.css');?>">
    <script src="<?= base_url('/article.js') ?>"></script>
  </head>
  <body>
    <div id="container">
      <header>
        <h1>CodeIgniter 4.6.1</h1>
      </header>
      <nav>
        <a href="<?= base_url('/admin/article'); ?>">Dashboard Admin</a>
        <a href="<?= base_url('/');?>" class="active">Home</a>
        <a href="<?= base_url('/article');?>">Article</a>
        <a href="<?= base_url('/about');?>">About</a>
        <a href="<?= base_url('/contact');?>">Contact</a>
        <a href="<?= base_url('/faqs');?>">FAQs</a>
        <a href="<?= base_url('/tos');?>">TOS</a>
      </nav>
      <section id="wrapper">
        <section id="main">