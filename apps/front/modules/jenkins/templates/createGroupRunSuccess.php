<?php /** @var GroupRunForm $form */ ?>
<?php /** @var array        $views */ ?>
<?php /** @var string       $default_active_view */ ?>

<?php echo $form->renderFormTag(url_for('jenkins/createGroupRun'), array('id' => 'groupRunForm')) ?>
  <div class="form-header">Create a build branch</div>
  
  <div class="form-content">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
  
    <div class="field">
      <?php echo $form['git_branch']->renderError() ?>
      <?php echo $form['git_branch']->renderLabel(); ?>
      <?php echo $form['git_branch']->render() ?>
    </div>
  
    <div class="field">
      <?php echo $form['label']->renderError() ?>
      <?php echo $form['label']->renderLabel(); ?>
      <?php echo $form['label']->render() ?>
    </div>  
  
    <div class="field">
      <?php echo $form['auto_launch']->renderError() ?>
      <?php echo $form['auto_launch']->renderLabel(); ?>
      <?php echo $form['auto_launch']->render() ?>
    </div>

    <div class="cart" jobscounter="42">
      <a href="#" title="Click to create" class="btn btn-primary btn-large">42</a>
    </div>
    <ul class="nav nav-tabs jenkins-view">
      <?php foreach ($views as $key => $view): ?>
        <li class="<?php $default_active_view==$view && print 'active'; ?>" view="<?php echo $view ?>">
          <a href="#" title="Afficher la vue <?php echo $view; ?>"><?php echo $view; ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
    
    <div class="jobs-container">
      
      <div class="actions">
        <a href="#" id="addViewAllJob" class="btn">Select all jobs</a>
        <a href="#" id="removeViewAllJob" class="btn">Unselect all jobs</a>
      </div>
      
      <?php foreach ($form['builds'] as $jobName => $widget): ?>
        <div class="jobs <?php isset($view_by_jobs[$jobName]) && print implode(' ', sfOutputEscaper::unescape($view_by_jobs[$jobName]));?>">
          <?php echo $widget['job_name']->renderError() ?>
          <?php echo $widget['job_name']->renderLabel(); ?>
          <?php echo $widget['job_name']->render() ?>
          
          <div class="parameters">
            <?php if (isset($widget['parameters'])): ?>
              <ul>
                <?php echo $widget['parameters']->renderError() ?>
                <?php echo $widget['parameters']->render() ?>
              </ul>
            <?php endif; ?>
          </div>
          
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="form-footer">
    <input type="submit" value="Create" class="btn btn-large btn-primary" /> 
  </div>
</form>
  
  
<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#groupRunForm').createGroupRunForm({
    });
  });
</script>
