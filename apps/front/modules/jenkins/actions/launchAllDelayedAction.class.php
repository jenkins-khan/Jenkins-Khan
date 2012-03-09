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
    $runs = JenkinsRunPeer::getDelayed($this->getUser());
    foreach ($runs as $run)
    {
      $run->launchDelayed($this->getJenkins());
      $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
    }
    $this->redirect('@homepage');
  }

}
