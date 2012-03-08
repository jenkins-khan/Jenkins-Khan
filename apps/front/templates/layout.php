<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" type="image/png"  href="<?php echo _compute_public_path('genghis', 'images', 'png') ?>" />
  <?php include_less_stylesheets() ?>
  <?php include_javascripts() ?>
</head>
<body>
<?php include_component_slot('menu') ?>
<?php include_component_slot('messages_flash') ?>

<?php echo $sf_content ?>
</body>
</html>
