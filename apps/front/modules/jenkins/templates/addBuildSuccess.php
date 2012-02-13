<?php /** @var BuildForm $form */ ?>
<?php /** @var array $group_run */ ?>

<?php echo $form->renderFormTag(url_for('jenkins/addBuild'), array('id' => 'addBuildForm')) ?>
  <?php echo $form->renderHiddenFields(); ?>

  <div class="form-header">
    Add a job to the build branch <?php echo get_partial('buildStatus', array('status' => $group_run['result'], 'label' => $group_run['label'])) ?> 
  </div>
  <div class="form-content">
    <div>
      <?php echo $form['job']->renderError() ?>
      <?php echo $form['job']->renderLabel(); ?>
      <?php echo $form['job']->render() ?>
    </div>
      
    <div>
      <?php echo $form['auto_launch']->renderError() ?>
      <?php echo $form['auto_launch']->renderLabel(); ?>
      <?php echo $form['auto_launch']->render() ?>
    </div>
      
    <div class="parameters">
      <ul>
        <?php foreach ($form['parameters'] as $jobName => $widgets): ?>
          <li class="<?php echo $jobName?>">
            <?php echo $widgets->renderError() ?>
            <?php echo $widgets->render() ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="form-footer">
    <input type="submit" name="add" value="Add" class="btn large primary"/>
    <input type="submit" name="add_and_continue" value="Add and continue" class="btn large primary"/>
  </div>
</form>

<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#addBuildForm').addBuildForm();
  });
</script>