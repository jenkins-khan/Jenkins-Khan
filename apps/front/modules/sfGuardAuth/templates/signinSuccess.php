<form class="login" action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <div class="form-header">Log in</div>
  <div class="form-content">
    <?php echo $form ?>
  </div>
  <div class="form-footer">
    <input class="btn btn-large btn-primary" type="submit" value="Log in" />
  </div>
</form>