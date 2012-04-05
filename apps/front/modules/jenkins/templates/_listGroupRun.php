<?php /** @var array $runs */ ?>
<?php /** @var array $current_group_run */ ?>
<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var bool $is_group_run_rebuildable */ ?>

<ul>
  <?php if (null === $current_group_run['id']): ?>
    <li>
      <div class="alert-message error">There is no build branch</div>
    </li>
  <?php else: ?>

      <li class="group_run_infos">
        <table>
          <tr>
            <td>
              Build git branch
            </td>
            <td>
              <div class="btn-group">
                <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                  <?php echo $current_group_run['git_branch'] ?>
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-right">
                  <?php if ($is_group_run_rebuildable): ?>
                    <li><?php echo link_to('Relaunch all jobs', url_for('branch_rebuild', array('git_branch_slug' => $current_group_run['git_branch_slug'])), array('title' => 'Relaunch all jobs')) ?></li>
                    <li><?php echo link_to('Add all jobs in delayed list', url_for('branch_rebuild_delayed', array('git_branch_slug' => $current_group_run['git_branch_slug'])), array('title' => 'Add all jobs in delayed list')) ?></li>
                   <?php endif ?>
                  <li><?php echo link_to('Add a job', $current_group_run['url_add_build'], array('title' => 'Add a job to this build branch')) ?></li>
                  <li><?php echo link_to('Duplicate build branch', $current_group_run['url_duplicate']) ?></li>
                  <li><?php echo link_to('Delete build branch', $current_group_run['url_delete']) ?></li>
                </ul>
              </div>
            </td>
          </tr>
          </table>
      </li>

      <?php foreach ($runs as $id => $run): ?>
      <li>
        <table class="run-infos">
          <tr>
            <td class="status"><?php include_partial('buildStatus', array('status' => $run['result'])) ?></td>
            <td class="name">
              <?php echo $run['url'] === null ? $run['job_name'] : link_to($run['job_name'], $run['url'], array('target' => '_blank', 'title' => 'See Jenkins job', 'class' => 'jenkins')); ?>
              <?php include_partial('buildParameters', array('parameters' => $run['parameters'])) ?>
            </td>
            <td class="infos">
              <?php $infos = array(); ?>
              <?php if (null !== $run['start_time']): $infos[] = 'Launched at '. $run['start_time']; endif; ?>
              <?php if (null !== $run['duration']): $infos[] = 'Duration: '. $run['duration']; endif; ?>
              <?php if (null !== $run['scheduled_launch']): $infos[] = 'Scheduled at '. $run['scheduled_launch']; endif; ?>
              <?php echo implode('<br />', $infos); ?>
            </td>
            <td class="job-progress">
              <?php if (null !== $run['progress']): ?>
                <?php $title = $run['progress'] . '% (Estimated remaining time: ' . $run['remaining_time'] . ')'; ?>
                <div class="progress progress-info progress-striped active" title="<?php echo $title ?>" >
                  <?php $linkApparence = '<div class="bar" style="width: ' . $run['progress'] . '%;"></div>'; ?>
                  <?php echo link_to($linkApparence, $run['url_console_log'], array('target' => '_blank')) ?>
                </div>
              <?php endif; ?>
            </td>
            <td class="bouton">
              <?php if ($run['is_cancelable']): ?>
                <?php echo link_to('Cancel build',  'jenkins/cancelRun?run_id='.$id, array('class' => 'cancel', 'title' => 'Cancel build')) ?>
              <?php endif; ?>
            </td>
            <td class="bouton">
              <?php if ($run['url_rebuild']): ?>
                <?php echo link_to('Relaunch', $run['url_rebuild'], array('class' => 'run-again', 'title' => (!$run['url_rebuild_delayed'] ? "Launch build immediatly" : 'Relaunch build'))) ?>
              <?php endif; ?>
            </td>
            <td class="actions">
              <div class="btn-group">
                <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-right">
                  <?php if ($run['url_rebuild_delayed']): ?>
                    <li><?php echo link_to('Delay', $run['url_rebuild_delayed'], array('title' => 'Relaunch build (delayed)')) ?></li>
                  <?php endif; ?>
                  <li>
                    <?php echo link_to('Remove build', $run['url_remove'], array('title' => 'Remove build from build branch', 'class' => 'remove-build')) ?>
                  </li>
                  <li><?php echo link_to('Go to console log', $run['url_console_log'], array('title' => 'View Jenkins console log', 'class' => 'jenkins', 'target' => '_blank')) ?></li>
                  <li><?php echo link_to('Go to test report', $run['url_test_report'], array('class' => 'jenkins','target' => '_blank')) ?></li>
                </ul>
              </div>
            </td>
          </tr>
        </table>
      </li>
      <?php endforeach; ?>
      <?php if ($jenkins->isAvailable()): ?>
        <li class="add-build">
          <?php echo link_to('Add a job to this build branch', $current_group_run['url_add_build'], array('class' => 'add-build', 'title' => 'Add a job to this build branch')); ?>
        </li>
      <?php else: ?>
        <li class="add-build"><a href="#" class="disabled">Add a job to this build branch</a></li>
      <?php endif; ?>

  <?php endif; ?>
</ul>
