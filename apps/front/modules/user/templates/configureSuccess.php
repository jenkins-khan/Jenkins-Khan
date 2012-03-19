<?php /** @var ProfileForm $form */ ?>
<?php /** @var string      $api_key */ ?>
<?php /** @var boolean     $api_enabled */ ?>

<?php echo $form->renderFormTag(url_for('user/configure')) ?>

  <div class="form-header">Configure your profile</div>
    
  <?php echo $form->renderHiddenFields(); ?>

  <div class="form-content">
    <div class="field">
      <?php echo $form['jenkins_url']->renderError() ?>
      <?php echo $form['jenkins_url']->renderLabel(); ?>
      <?php echo $form['jenkins_url']->render() ?>
    </div>
  </div>
  
  <div class="form-footer">
    <input type="submit" value="Save" class="btn btn-large btn-primary" />
  </div>

  <?php if ($api_enabled): ?>
  <div class="form-header">Api Key</div>

  <p>
    <?php if(null === $api_key): ?>
      No api key definded.
    <?php else: ?>
      Api Key : <?php echo $api_key ?>
    <?php endif; ?>
  </p>

  <div class="form-footer">
    <?php if(null !== $api_key): ?>
    <a href="<?php echo url_for('user/apiKeyRemove') ?>" class="btn btn-large btn-primary">Remove</a>
    <?php endif; ?>
    <a href="<?php echo url_for('user/apiKeyGenerate') ?>" class="btn btn-large btn-primary">Generate</a>
  </div>
  <?php endif; ?>

</form>
