<?php
  sfContext::getInstance()->getConfiguration()->loadHelpers(array('Tag', 'Asset',));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="title" content="Jenkins Khan" />
  <title>Jenkins Khan</title>
  <link rel="shortcut icon" type="image/png"  href="<?php echo image_path('genghis.png') ?>" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo stylesheet_path('less/static.css') ?>" />
</head>
<body>
<div class="topbar">
  <div class="topbar-inner">
    <div class="container-fluid">
      <h1><a class="brand" href="#">Jenkins Khan</a></h1>
    </div>
  </div>
</div>

<div class="error-container">
  <h3>Jenkins Khan is angry</h3>
  <?php if (isset($exception)): ?>
    <ul>
      <li>Message : <?php echo $exception->getMessage(); ?></li>
    </ul>
  <?php endif; ?>
  
</div>
</body>
</html>
