<?php /** @var DelayedRunForm    $form */ ?>
<?php /** @var array             $delayed_runs */ ?>
<?php /** @var durationFormatter $duration_formatter */ ?>

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
          <?php echo $widget['launch_job']->render() ?>
          <?php include_partial(
            'buildStatus',
            array(
              'status' => $delayed_runs[$id]['group_run_result'],
              'label'  => $delayed_runs[$id]['group_run_label'],
              'url'    => $delayed_runs[$id]['group_run_url'],
            )
          ); ?>
          <?php echo $widget['launch_job']->renderLabel() ?>
          <p class="last-duration">
            <?php if (strlen($delayed_runs[$id]['last_duration'])): ?>
            Last duration: <?php echo $duration_formatter->formatte($delayed_runs[$id]['last_duration'], ESC_RAW) ?>
            <?php endif; ?>
          </p>
          <div class="timepicker-container input-append">
            <?php echo $widget['scheduled_at']->renderLabel() ?>
            <?php echo $widget['scheduled_at']->render() ?>
            <span class="add-on" title="Clear starting time">
              <span class="jk-icon-clock-delete"></span>
            </span>
            <?php echo $widget['scheduled_at']->renderError() ?>
          </div>
          <?php include_partial('buildParameters', array('parameters' => $delayed_runs[$id]['parameters'])) ?>
        </li>
      <?php endforeach; ?>
      </ol>
    <?php endif; ?>
  </div>
  <?php if (count($form['runs']) > 0): ?>
  <div class="form-footer">
    <input type="submit" name="delete_delayed" value="Delete" class="btn btn-large btn-primary btn-delete"/>
    <input type="submit" name="launch_delayed" value="Launch" class="btn btn-large btn-primary"/>
  </div>
  <?php endif; ?>
</form>


<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#delayedForm').delayedRun();
  });
</script>