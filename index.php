<?php require_once ('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once ('inc/header.php') ?>

<body>
  <?php $page = isset($_GET['p']) ? $_GET['p'] : 'home'; ?>


  <?php
  if (!file_exists($page . ".php") && !is_dir($page)) {
    include '404.html';
  } else {
    if (is_dir($page))
      include $page . '/index.php';
    else
      include $page . '.php';

  }
  ?>
  <?php require_once ('inc/footer.php') ?>


</body>

</html>