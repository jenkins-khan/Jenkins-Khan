<?php
 
class rebuildAction extends baseJenkinsAction
{

  /**
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  public function execute($request)
  {
    $message = 'run_id or group_run_id parameter is required';
    $this->forward404Unless($request->hasParameter('run_id') xor $request->hasParameter('group_run_id'), $message);
    if ($request->hasParameter('run_id'))
    {
      $this->forward404Unless($request->hasParameter('run_id'), 'run_id parameter is required');
      $runId      = $request->getParameter('run_id');
      $jenkinsRun = $this->rebuildRun($request, $runId);
      $this->getUser()->setFlash('notice', sprintf('Build [%s] has been relaunched', $jenkinsRun->getJobName()));
      $this->redirect(sprintf('jenkins/index?group_run_id=%s', $jenkinsRun->getJenkinsGroupRunId()));
    }
    else
    {
      $this->forward404Unless($request->hasParameter('group_run_id'), 'group_run_id parameter is required');
      $groupRunId = $request->hasParameter('group_run_id');
      $jenkinsGroupRun = $this->rebuildGroupRun($groupRunId);
      $this->forward404Unless($jenkinsGroupRun !== null, sprintf('group_run_id %s not found', $groupRunId));
      $this->getUser()->setFlash('notice', sprintf('Build branch [%s] has been relaunched', $jenkinsGroupRun->getLabel()));
      $this->redirect(sprintf('jenkins/index?group_run_id=%s', $jenkinsGroupRun->getId()));
    }
  }

  /**
   * @param int $groupRunId
   *
   * @return JenkinsGroupRun
   */
  protected function rebuildGroupRun($groupRunId)
  {
    $jenkinsGroupRun = JenkinsGroupRunPeer::retrieveByPK($groupRunId);
    if (null == $jenkinsGroupRun)
    {
      return null;
    }
    $criteria = new Criteria();
    $criteria->add(JenkinsRunPeer::JENKINS_GROUP_RUN_ID, $groupRunId);
    $runs = JenkinsRunPeer::doSelect($criteria);
    /** @var JenkinsRun $run */
    foreach ($runs as $run)
    {
      $this->rebuildRun($run->getId());
    }
    return $jenkinsGroupRun;
  }

  /**
   * @param int $runId
   *
   * @return JenkinsRun
   */
  protected function rebuildRun($runId)
  {
    $jenkinsRun = JenkinsRunPeer::retrieveByPK($runId);

    $this->forward404Unless(
      $jenkinsRun instanceOf JenkinsRun,
      sprintf('Can\'t create JenkinsRun with id %s', $runId)
    );

    $jenkins         = $this->getJenkins();
    $build           = $jenkinsRun->getJenkinsBuild($jenkins);

    $inputParameters = array();
    if (null !== $build)
    {
      //peu importe ce qui est stocké dans la base => Jenkins fait toujours foi
      $inputParameters = $build->getInputParameters();
    }

    //désactivation du numéro de run
    $jenkinsRun->setJobBuildNumber(null);
    $jenkinsRun->save();

    $jenkinsRun->launch($this->getJenkins(), $inputParameters);

    $jenkins->launchJob($jenkinsRun->getJobName(), $inputParameters);

    return $jenkinsRun;
  }


}
