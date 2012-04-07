<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var array   $group_runs */ ?>
<?php /** @var int     $current_group_run_id */ ?>
<?php /** @var string  $sort_type */ ?>
<?php /** @var string  $sort_direction */ ?>
<?php /** @var array   $sort_menu */ ?>
<?php /** @var string  $branch_view_url */ ?>
<?php /** @var string  $partial_url_for_sort_direction */ ?>
<?php /** @var boolean $enabled_popover */ ?>

<div id="dashboard">

  <div class="sidebar">
    <ul>
      <?php if (null !== $sort_menu): ?>
      <li class="sidebar-actions">
        <div class="btn-group">
          <a class="btn btn-jenkins-khan sort-cancel" href="<?php echo $branch_view_url; ?>" title="Clear sorting">&times;</a>
          <a class="btn btn-jenkins-khan dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo ($sort_type == 'none' || null === $sort_type) ? 'Sort by' : 'Sorted by ' . ucwords($sort_menu[$sort_type]['label']) ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu dropdown-left">
            <?php foreach ($sort_menu as $sorter): ?>
              <li><?php echo link_to($sorter['label'], $sorter['url']) ?></li>
            <?php endforeach; ?>
          </ul>


          <div class="buttons-radio" data-toggle="buttons-radio">
            <button
              class="btn btn-jenkins-khan sort<?php ('desc' == $sort_direction) && print ' active'; ?>"
              value="<?php echo $partial_url_for_sort_direction; ?>desc"
              title="Sort descending"
              >
              <span class="jk-icon-sort-descending">Desc</span>
            </button>
            <button
              class="btn btn-jenkins-khan sort<?php ('desc' != $sort_direction) && print ' active'; ?>"
              value="<?php echo $partial_url_for_sort_direction; ?>asc"
              title="Sort ascending"
              >
              <span class="jk-icon-sort-ascending">Asc</span>
            </button>
          </div>
        </div>
      </li>
      <?php endif ?>
      <li>
        <?php if ($jenkins->isAvailable()): ?>
          <?php echo link_to('Create a build branch', 'jenkins/createGroupRun', array('class' => 'add-run')); ?>
        <?php else: ?>
          <a href="#" class="add-run disabled">Create a build branch</a>
        <?php endif; ?>
      </li>
      <?php foreach ($group_runs as $id => $group_run): ?>
        <li>
          <?php $branchName = get_partial('buildStatus', array('status' => $group_run['result'], 'label' => $group_run['label'])); ?>
          <?php include_partial(
            'groupRunDetails',
            array(
              'date'   => $group_run['date'],
              'id'     => ('popover_' . $group_run['git_branch']),
              'status' => $group_run['result']
            )
          ); ?>
          <?php $popover_options = array(
            'title'                => $group_run['git_branch'] . ' branch',
            'data-popover-content' => 'popover_' . $group_run['git_branch'],
          ); ?>
          <?php $link_options = array(
            'class'                => $id == $current_group_run_id ? 'group-run active' : 'group-run',
            'title'                => '',
            'data-popover-content' => '',
          ); ?>
          <?php echo link_to($branchName, $group_run['url_view'], array_merge($link_options, ($enabled_popover) ? $popover_options : array())) ?>
          <?php echo link_to(' ', 'jenkins/deleteGroupRun?id='.$id, array('class' => 'delete-group-run', 'title' => 'Delete build branch')) ?>
          <?php echo link_to(' ', 'jenkins/createGroupRun?from_group_run_id='.$id, array('class' => 'duplicate-group-run', 'title' => 'Duplicate build branch')) ?>
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
      urlReloadListGroupRun: '<?php echo url_for('jenkins/listGroupRun?group_run_id=' . $current_group_run_id); ?>',
      popoverEnabled: <?php echo ($enabled_popover) ? 1 : 0; ?>
    });
  });
</script>

<?php include_partial('help/modal', array('name' => 'shortcuts', 'id' => 'shortcuts-help')) ?>
