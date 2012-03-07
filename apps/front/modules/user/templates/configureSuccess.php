<?php /** @var ProfileForm $form */ ?>

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
</form>