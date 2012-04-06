<?php

class launchAllDelayedAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $numDelayedJobs = 0;
    $runs = JenkinsRunPeer::getDelayed($this->getUser());
    foreach ($runs as $run)
    {
      $run->launchDelayed($this->getJenkins());
      $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
      $numDelayedJobs++;
    }
    if ($numDelayedJobs > 1)
    {
      $this->getUser()->setFlash('info', sprintf('[%d] delayed jobs have been launched', $numDelayedJobs));
    }
    else if ($numDelayedJobs == 1)
    {
      $this->getUser()->setFlash('info', '[1] delayed job has been launched');
    }
    else
    {
      $this->getUser()->setFlash('info', 'There was no delayed job to launch');
    }
    $this->redirect('@homepage');
  }

}
