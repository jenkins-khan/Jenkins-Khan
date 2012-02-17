<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php $path = sfConfig::get('sf_relative_url_root', preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : ''))) ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="title" content="Jenkins Khan" />
  <title>Jenkins Khan</title>
  <link rel="shortcut icon" type="image/png"  href="<?php echo $path ?>/images/genghis.png" />
  <link rel="stylesheet" type="text/css" media="screen"  href="<?php echo $path ?>/css/less/static.css" />
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
  <h3><img src="<?php echo $path ?>/images/genghis.png" title="Jenkins Khan"/>Jenkins Khan is angry</h3>
  <h5>The server returned a "<?php echo $code ?> <?php echo $text ?>".</h5>
  
  <dl class="informations">
    <?php if (isset($exception)): ?>
      <dt>Message</dt>
      <dd><?php echo $exception->getMessage() ?></dd>
    <?php endif; ?>
    <dt>Something is broken</dt>
    <dd>Don't hesitate to fill an issue on <a href="https://github.com/pmsipilot/Jenkins-Khan" target="_blank">GitHub</a></dd>

    <dt>What's next</dt>
    <dd>
      <ul class="actions">
        <li><a href="javascript:history.go(-1)">Back to previous page</a></li>
        <li><a href="<?php echo $path; ?>">Go to Homepage</a></li>
      </ul>
    </dd>
  </dl>
</div>
</body>
</html>
