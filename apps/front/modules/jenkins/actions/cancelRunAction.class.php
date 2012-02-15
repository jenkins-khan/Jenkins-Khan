<?php
 
class cancelRunAction extends baseJenkinsAction
{

  /**
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $this->forward404Unless($request->hasParameter('run_id'), 'run_id parameter is required');
    $jenkinsRun = JenkinsRunPeer::retrieveByPK($request->getParameter('run_id'));

    $this->forward404Unless(
      $jenkinsRun instanceOf JenkinsRun,
      sprintf('can\'t create JenkinsRun with id %s', $request->getParameter('run_id'))
    );
    
    $jenkins           = $this->getJenkins();
    $jenkinsGroupRunId = $jenkinsRun->getJenkinsGroupRunId();

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
      $this->getUser()->setFlash('notice', sprintf('Build [%s] has been removed from Jenkins queue.', $jenkinsRun->getJobName()));
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

    $this->redirect(sprintf('jenkins/index?group_run_id=%s', $jenkinsGroupRunId));
  }
}
