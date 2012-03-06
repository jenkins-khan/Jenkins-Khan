<?php /** @var DelayedRunForm $form */ ?>
<?php /** @var array          $delayed_runs */ ?>

<?php echo $form->renderFormTag(url_for('jenkins/delayed'), array('id' => 'delayedForm')) ?>
  <div class="form-header">
    Delayed jobs list
  </div>
  <div class="form-content">
    <?php echo $form->renderHiddenFields(); ?>
    <?php echo $form->renderGlobalErrors(); ?>
  
    <?php if (0 === count($form['runs'])): ?>
      <div class="alert-message warning">There is no job in delayed list</div>
    <?php else: ?>
      <div class="actions">
        <a href="#" id="addViewAllRun" class="btn">Select all jobs</a>
        <a href="#" id="removeViewAllRun" class="btn">Unselect all jobs</a>
      </div>
  
      <ol class="delayed-runs">
      <?php foreach ($form['runs'] as $id => $widget): ?>
        <?php /** @var sfWidgetFormInputCheckbox $widget */ ?>
        <li class="delayed-run">
          <?php echo $widget->render() ?>
          <?php include_partial('buildStatus', array('status' => $delayed_runs[$id]['group_run_result'], 'label' => $delayed_runs[$id]['group_run_label'])); ?>
          <?php echo $widget->renderLabel() ?>
          <?php include_partial('buildParameters', array('parameters' => $delayed_runs[$id]['parameters'])) ?>
        </li>
      <?php endforeach; ?>
      </ol>
    <?php endif; ?>
  </div>
  <div class="form-footer">
    <input type="submit" value="Launch" class="btn btn-large btn-primary"/>
  </div>
</form>


<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#delayedForm').delayedRun();
  });
</script>