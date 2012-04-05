<?php

class listGroupRunComponent extends sfComponent
{

  /**
   * @param sfWebRequest $request
   */
  public function execute($request)
  {
    /** @var Jenkins $jenkins */
    $groupRunId = $this->getVar('group_run_id');
    $jenkins    = $this->getVar('jenkins');

    $currentGroup = JenkinsGroupRunPeer::retrieveByPK($groupRunId);

    if (null === $currentGroup)
    {
      return sfView::NONE;
    }

    $durationFormatter     = new durationFormatter();
    $runs                  = $currentGroup->getJenkinsRuns();
    $dataRuns              = array();
    $isGroupRunRebuildable = false;

    foreach ($runs as $run)
    {
      $isCancelable = false;
      $progress     = null;
      $remaining    = null;
      $isRunning    = false;
      $urlBuild     = $run->getUrlBuild($jenkins);
      if (!$jenkins->isAvailable())
      {
        $isCancelable = false;
      }
      elseif (($build = $run->getJenkinsBuild($jenkins)) instanceof Jenkins_Build)
      {
        /** @var Jenkins_Build $build */
        $isCancelable = $isRunning = $build->isRunning();
        $progress     = $build->getProgress();
        $remaining    = $build->getRemainingExecutionTime();
      }
      elseif ($run->isInJenkinsQueue($jenkins))
      {
        $isCancelable = true;
      }

      if ($run->isRebuildable())
      {
        $isGroupRunRebuildable = true;
      }


      /** @var JenkinsRun $run */
      $dataRuns[$run->getId()] = array(
        'job_name'            => $run->getJobName(),
        'start_time'          => $run->getStartTime($jenkins),
        'duration'            => $durationFormatter->formatte($run->getDuration($jenkins)),
        'scheduled_launch'    => $run->getLaunchDelayed(),
        'result'              => $run->getJenkinsResult($jenkins),
        'parameters'          => $run->getLaunched() && $isRunning ? $run->getJenkinsBuildCleanedParameter($jenkins) : $run->decodeParameters(),
        'is_running'          => $isRunning,
        'progress'            => $progress,
        'remaining_time'      => null === $remaining ? $remaining : $durationFormatter->formatte($remaining),
        'is_cancelable'       => $isCancelable,
        'url'                 => $urlBuild,
        'url_console_log'     => $urlBuild . '/console',
        'url_test_report'     => $urlBuild . '/testReport',
        'url_remove'          => $this->generateUrl('run_remove', $run),
        'url_rebuild'         => !$isRunning ? $this->generateUrl('run_rebuild', $run) : false,
        'url_rebuild_delayed' => !$isRunning && $run->isRebuildable() ? $this->generateUrl('run_rebuild_delayed', $run) : false,
      );
    }

    $this->setVar('is_group_run_rebuildable', $isGroupRunRebuildable);
    $this->setVar('runs', $dataRuns);
    $this->setVar('current_group_run', array(
      'id'              => null === $currentGroup ? null : $currentGroup->getId(),
      'git_branch'      => null === $currentGroup ? null : $currentGroup->getGitBranch(),
      'git_branch_slug' => null === $currentGroup ? null : $currentGroup->getGitBranchSlug(),
      'url_add_build'   => null === $currentGroup ? null : 'jenkins/addBuild?group_run_id='.$currentGroup->getId(),
      'url_duplicate'   => null === $currentGroup ? null : 'jenkins/createGroupRun?from_group_run_id=' . $currentGroup->getId(),
      'url_delete'      => null === $currentGroup ? null : 'jenkins/deleteGroupRun?id=' . $currentGroup->getId(),
    ));
  }

}
