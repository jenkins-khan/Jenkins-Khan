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
      <?php include_partial('help/link-modal', array('name' => 'user/configure/jenkins-url', 'title' => 'Jenkins Url')) ?>
    </div>
  </div>

  <div class="form-footer">
    <input type="submit" value="Save" class="btn btn-large btn-primary" />
  </div>

</form>

<?php if ($api_enabled): ?>
  <form action="<?php echo url_for('user/apiKeyGenerate') ?>" method="POST" id="form_configure_api">
    <div class="form-header">Api Key</div>

      <div class="form-content">
        <?php if(null === $api_key): ?>
          You haven't created an api key yet.
        <?php else: ?>
          <div class="field">
            <label for="form_configure_api_api_key">Api Key</label>
            <input type="text" name="form_configure_api[api_key]" id="form_configure_api_api_key" value="<?php echo $api_key ?>" readonly="readonly" size="32"/>
          </div>
        <?php endif; ?>
      </div>

      <div class="form-footer">
        <?php if(null !== $api_key): ?>
          <a href="<?php echo url_for('user/apiKeyRemove') ?>" class="btn btn-large btn-primary">Remove</a>
        <?php endif; ?>
        <input type="submit" value="Generate" class="btn btn-large btn-primary" />
      </div>
  </form>
<?php endif; ?>

