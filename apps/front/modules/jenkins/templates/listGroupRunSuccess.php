<?php /** @var Jenkins $jenkins */ ?>
<?php /** @var int $current_group_run_id */ ?>

<?php include_component('jenkins', 'listGroupRun', array('group_run_id' => $current_group_run_id, 'jenkins' => $jenkins)); ?>