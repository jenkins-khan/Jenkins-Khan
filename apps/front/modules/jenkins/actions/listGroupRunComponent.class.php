<?php
 
class listGroupRunComponent extends sfComponent
{

  /**
   * Execute any application/business logic for this component.
   *
   * In a typical database-driven application, execute() handles application
   * logic itself and then proceeds to create a model instance. Once the model
   * instance is initialized it handles all business logic for the action.
   *
   * A model should represent an entity in your application. This could be a
   * user account, a shopping cart, or even a something as simple as a
   * single product.
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    /** @var Jenkins $jenkins */
    $groupRunId = $this->getVar('group_run_id');
    $jenkins    = $this->getVar('jenkins');
    
    $criteriaRun = new Criteria();
    $criteriaRun->add(JenkinsRunPeer::JENKINS_GROUP_RUN_ID, $groupRunId);

    $durationFormatter = new durationFormatter();
    
    $runs     = JenkinsRunPeer::doSelect($criteriaRun);
    $dataRuns = array();
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
      
      /** @var JenkinsRun $run */
      $dataRuns[$run->getId()] = array(
        'job_name'         => $run->getJobName(),
        'start_time'       => $run->getStartTime($jenkins),
        'duration'         => $durationFormatter->formatte($run->getDuration($jenkins)),
        'result'           => $run->getJenkinsResult($jenkins),
        'parameters'       => $run->getLaunched() ? $run->getJenkinsBuildCleanedParameter($jenkins) : $run->decodeParameters(),
        'url'              => $run->getUrlBuild($jenkins),
        'is_cancelable'    => $isCancelable,
        'is_rebuildable'   => $run->getLaunched(),
        'is_running'       => $isRunning,
        'progress'         => $progress,
      );
    }
    
    
    $currentGroup = JenkinsGroupRunPeer::retrieveByPK($groupRunId);

    $this->setVar('runs', $dataRuns);
    $this->setVar('current_group_run', array(
      'id'         => null === $currentGroup ? null : $currentGroup->getId(),
      'git_branch' => null === $currentGroup ? null : $currentGroup->getGitBranch(),
    ));
  }
}
