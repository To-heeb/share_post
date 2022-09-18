<?php require_once APPROOT . '/views/inc/header.php'; ?>
<h1><?= $data['title'] ?></h1>
<p><?php echo $data['description']; ?></p>
<p>Version: <strong><?php echo APP_VERSION; ?></strong></p>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>