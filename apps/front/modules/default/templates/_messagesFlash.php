<?php foreach (array('info', 'notice', 'warning', 'error') as $level): ?>
<?php if ($sf_user->hasFlash($level)): ?>
  <div class="alert alert-<?php echo $level; ?>">
    <?php echo $sf_user->getFlash($level) ?>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
