<?php
 
class cancelRunAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed|void A string containing the view name associated with this action|void
   * @throws Exception
   */
  function execute($request)
  {
    $jenkins = $this->getJenkins();
    
    if ($request->hasParameter('group_run_id'))
    {
      $jenkinsGroupRun = JenkinsGroupRunPeer::retrieveByPK($request->getParameter('group_run_id'));
      $this->forward404Unless(
        $jenkinsGroupRun instanceOf JenkinsGroupRun,
        sprintf('can\'t create JenkinsGroupRun with id %s', $request->getParameter('group_run_id'))
      );
      $jenkinsRuns = $jenkinsGroupRun->getJenkinsRuns();
      foreach ($jenkinsRuns as $jenkinsRun)
      {
        $this->cancelJenkinsRun($jenkinsRun, $jenkins);
      }
      $this->getUser()->setFlash('info', sprintf('All builds of group [%s] have been canceled', $jenkinsGroupRun->getLabel()));
      $this->redirect($this->generateUrl('branch_view', $jenkinsGroupRun));
    }
    elseif ($request->hasParameter('run_id'))
    {
      $jenkinsRun = JenkinsRunPeer::retrieveByPK($request->getParameter('run_id'));
      $this->forward404Unless(
        $jenkinsRun instanceOf JenkinsRun,
        sprintf('can\'t create JenkinsRun with id %s', $request->getParameter('run_id'))
      );
      $this->cancelJenkinsRun($jenkinsRun, $jenkins);
      $this->redirect($this->generateUrl('branch_view', $jenkinsRun->getJenkinsGroupRun()));
    }
    else
    {
      throw new Exception('run_id or group_run_id parameter is required');
    }
  }

  /**
   * @param JenkinsRun $jenkinsRun
   * @param Jenkins    $jenkins
   */
  private function cancelJenkinsRun(JenkinsRun $jenkinsRun, Jenkins $jenkins)
  {
    if (($build = $jenkinsRun->getJenkinsBuild($jenkins)) instanceof Jenkins_Build)
    {
      /** @var Jenkins_Build $build */
      if (null !== ($executor = $build->getExecutor()))
      {
        /** @var Jenkins_Executor $executor */
        $executor->stop();
        $this->getUser()->setFlash('info', sprintf('Build [%s] has been canceled', $jenkinsRun->getJobName()));
      }
      else
      {
        $this->getUser()->setFlash(
          'warning', 
          sprintf('Build [%s] can\'t be canceled, because this build is not running', $jenkinsRun->getJobName())
        );
      }
    }
    elseif (null !== ($jobQueue = $jenkinsRun->getJenkinsQueue($jenkins)))
    {
      $jobQueue->cancel();
      $jenkinsRun->computeJobBuildNumber($jenkins, $this->getUser());
      $this->getUser()->setFlash('info', sprintf('Build [%s] has been removed from Jenkins queue.', $jenkinsRun->getJobName()));
    }
    else
    {
      $this->getUser()->setFlash(
        'warning',
        sprintf(
          'Build [%s] can\'t be canceled, because it can\'t be possible to associate it to a Jenkins Build.
          Maybe he is now in Jenkins queue.', $jenkinsRun->getJobName()
        )
      );
    }
  }
  
}
