<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var array $group_runs */ ?>
<?php /** @var int $current_group_run_id */ ?>

<div id="dashboard">

  <?php if ($jenkins->isAvailable()): ?>
    <li>
      <div class="alert-message success"><?php echo link_to('Jenkins', $jenkins->getUrl(), array('title' => 'Open Jenkins', 'class' => 'jenkins')) ?> is running.</div>
    </li>
  <?php endif; ?>
  
  <div class="sidebar">
    <ul>
      <li>
        <?php if ($jenkins->isAvailable()): ?>
          <?php echo link_to('Create a build branch', 'jenkins/createGroupRun', array('class' => 'add-run')); ?>
        <?php else: ?>
          <a href="#" class="add-run disabled">Create a build branch</a>
        <?php endif; ?>
      </li>
      <?php foreach ($group_runs as $id => $group_run): ?>
        <li>
          <?php echo link_to(get_partial('buildStatus', array('status' => $group_run['result'], 'label' => $group_run['label'])), 'jenkins/index?group_run_id=' . $id, array('class' => $id == $current_group_run_id ? 'group-run active' : 'group-run')); ?>
          <?php echo link_to(' ',  'jenkins/deleteGroupRun?id='.$id, array('class' => 'delete-group-run', 'title' => 'Delete build branch')) ?>
          <?php echo link_to(' ',  'jenkins/createGroupRun?from_group_run_id='.$id, array('class' => 'duplicate-group-run', 'title' => 'Duplicate build branch')) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="content">
    <?php include_component('jenkins', 'listGroupRun', array('group_run_id' => $current_group_run_id, 'jenkins' => $jenkins)); ?>
  </div>
</div>
<script language="javascript" type="text/javascript">
  $(document).ready(function(){
    $('#dashboard').jenkinsDashboard({
      urlReloadListGroupRun: '<?php echo url_for('jenkins/listGroupRun?group_run_id=' . $current_group_run_id); ?>' 
    });
  });
</script>
