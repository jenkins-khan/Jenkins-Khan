<?php /** @var array $runs */ ?>
<?php /** @var array $current_group_run */ ?>
<?php /** @var Jenkins $jenkins */ ?>

<ul>
  <?php if (null === $current_group_run['id']): ?>
    <li>
      <div class="alert-message error">There is no build branch</div>
    </li>
  <?php else: ?>
    
      <li class="group_run_infos">
        Build git branch : <?php echo $current_group_run['git_branch'] ?>
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
              <?php if ($run['is_rebuildable']): ?>
                <?php echo link_to('Relaunch',  'jenkins/rebuild?run_id='.$id, array('class' => 'run-again', 'title' => 'Relaunch build')) ?>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </li>
      <?php endforeach; ?>
      <?php if ($jenkins->isAvailable()): ?>  
        <li class="add-build">
          <?php echo link_to('Add a job to this build branch', 'jenkins/addBuild?group_run_id='.$current_group_run['id'], array('class' => 'add-build', 'title' => 'Add a job to this build branch')); ?>
        </li>
      <?php else: ?>
        <li class="add-build"><a href="#" class="disabled">Add a job to this build branch</a></li>
      <?php endif; ?>
  
  <?php endif; ?>
</ul>
