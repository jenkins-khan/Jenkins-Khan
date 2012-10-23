<?php

class cleanAllDelayedAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $runs           = JenkinsRunPeer::getDelayed($this->getUser());
    $numDelayedJobs = count($runs);
    foreach ($runs as $run)
    {
      $run->setLaunched(1);
      $run->setJobBuildNumber(null);
      $run->save();
      $numDelayedJobs--;
    }
    if ($numDelayedJobs > 0)
    {
      $this->getUser()->setFlash('info', sprintf('[%d] delayed jobs have been deleted', $numDelayedJobs));
    }
    else
    {
      $this->getUser()->setFlash('info', 'All delayed jobs have been deleted');
    }
    $this->redirect('@homepage');
  }

}
