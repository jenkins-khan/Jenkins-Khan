<?php foreach (array('notice', 'warning', 'error') as $level): ?>
<?php if ($sf_user->hasFlash($level)): ?>
  <div class="alert-message <?php echo $level; ?>">
    <p><?php echo $sf_user->getFlash($level) ?></p>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
