<?php /** @var array $runs */ ?>
<?php /** @var array $current_group_run */ ?>
<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var durationFormatter $duration_formatter */ ?>

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
            <?php include_partial('default/btnGroup', array(
              'label' => $current_group_run['git_branch'],
              'align_right' => true,
              'links' => $current_group_run['dropdown_links'],
              'class' => 'btn-jenkins-khan',
            ));?>
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
              <?php if (null !== $run['duration']): $infos[] = 'Duration: '. $duration_formatter->formatte($run['duration'], ESC_RAW); endif; ?>
              <?php if (null !== $run['scheduled_launch']): $infos[] = 'Scheduled at '. $run['scheduled_launch']; endif; ?>
              <?php echo implode('<br />', $infos); ?>
            </td>
            <td class="job-progress">
              <?php if (null !== $run['progress']): ?>
                <?php $title = $run['progress'] . '% (Estimated remaining time: ' . $duration_formatter->formatte($run['remaining_time'], ESC_RAW) . ')'; ?>
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
                <?php echo link_to('Relaunch', $run['url_rebuild'], array(
                  'class' => 'run-again', 
                  'title' => $run['title_url_rebuild'],
                )); ?>
              <?php endif; ?>
            </td>
            <td class="actions">
              <?php include_partial('default/btnGroup', array(
                'links' => $run['dropdown_links'],
                'align_right' => true,
                'class' => 'btn-jenkins-khan',
              ));?>
            </td>
          </tr>
        </table>
      </li>
      <?php endforeach; ?>
      <?php if (isset($current_group_run['url_add_build'])): ?>
        <li class="add-build">
          <?php echo link_to('Add a job to this build branch', $current_group_run['url_add_build'], array('class' => 'add-build', 'title' => 'Add a job to this build branch')); ?>
        </li>
      <?php else: ?>
        <li class="add-build"><a href="#" class="disabled">Add a job to this build branch</a></li>
      <?php endif; ?>

  <?php endif; ?>
</ul>
