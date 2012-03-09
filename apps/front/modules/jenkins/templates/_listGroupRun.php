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
                <ul class="dropdown-menu">
                  <?php if ($is_group_run_rebuildable): ?>
                    <li><?php echo link_to('Relaunch', url_for('branch_rebuild', array('branch_name' => $current_group_run['git_branch'])), array('title' => 'Relaunch build branch')) ?></li>
                    <li><?php echo link_to('Relaunch (delayed)', url_for('branch_rebuild_delayed', array('branch_name' => $current_group_run['git_branch'])), array('title' => 'Relaunch build branch (delayed)')) ?></li>
                   <?php endif ?>
                  <li><?php echo link_to('Add job', $current_group_run['url_add_build'], array('title' => 'Add job to this build branch')) ?></li>
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
              <?php if (null !== $run['start_time']): ?>Launched at <?php echo $run['start_time'] ?><?php endif; ?>
              <?php if (null !== $run['duration']): ?><br />Duration : <?php echo $run['duration'] ?><?php endif; ?>
            </td>
            <td class="job-progress">
              <?php if (null !== $run['progress']): ?>
                <div class="progress progress-info progress-striped">
                  <div class="bar" style="width: <?php echo $run['progress'] ?>%;"></div>
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
                <?php echo link_to('Relaunch', $run['url_rebuild'], array('class' => 'run-again', 'title' => 'Relaunch build')) ?>
              <?php endif; ?>
            </td>
            <td class="actions">
              <div class="btn-group">
                <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                <ul class="dropdown-menu ">
                  <?php if ($run['url_rebuild_delayed']): ?>
                    <li><?php echo link_to('Delay', $run['url_rebuild_delayed'], array('title' => 'Relaunch build (delayed)')) ?></li>
                  <?php endif; ?>
                  <li><?php echo link_to('Go to console log', $run['url_console_log']) ?></li>
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
