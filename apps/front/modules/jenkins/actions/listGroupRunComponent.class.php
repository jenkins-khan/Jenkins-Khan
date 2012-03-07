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

    $currentGroup          = JenkinsGroupRunPeer::retrieveByPK($groupRunId);

    if (null === $currentGroup)
    {
      throw new sfException(sprintf('group run "%s" not found', $groupRunId));
    }

    $durationFormatter     = new durationFormatter();
    $runs                  = $currentGroup->getJenkinsRuns();
    $dataRuns              = array();
    $isGroupRunRebuildable = false;

    foreach ($runs as $run)
    {
      $isCancelable = false;
      $progress     = null;
      $isRunning    = false;
      if (!$jenkins->isAvailable())
      {
        $isCancelable = false;
      }
      elseif (($build = $run->getJenkinsBuild($jenkins)) instanceof Jenkins_Build)
      {
        /** @var Jenkins_Build $build */
        $isCancelable = $isRunning = $build->isRunning();
        $progress     = $build->getProgress();
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
        'result'              => $run->getJenkinsResult($jenkins),
        'parameters'          => $run->getLaunched() ? $run->getJenkinsBuildCleanedParameter($jenkins) : $run->decodeParameters(),
        'url'                 => $run->getUrlBuild($jenkins),
        'url_console_log'     => $run->getUrlBuild($jenkins) . '/console',
        'is_cancelable'       => $isCancelable,
        'url_rebuild'         => $run->isRebuildable() ? $this->generateUrl('run_rebuild', $run) : false,
        'url_rebuild_delayed' => $run->isRebuildable() ? $this->generateUrl('run_rebuild_delayed', $run) : false,
        'is_running'          => $isRunning,
        'progress'            => $progress,
      );
    }

    $this->setVar('is_group_run_rebuildable', $isGroupRunRebuildable);
    $this->setVar('runs', $dataRuns);
    $this->setVar('current_group_run', array(
      'id'         => null === $currentGroup ? null : $currentGroup->getId(),
      'git_branch' => null === $currentGroup ? null : $currentGroup->getGitBranch(),
    ));
  }

}
