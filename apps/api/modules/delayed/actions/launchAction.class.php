<?php

class launchAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return string
   */
  public function execute($request)
  {
    try
    {
      $runs = JenkinsRunPeer::getDelayed($this->getUser());
      foreach ($runs as $run)
      {
        $run->launchDelayed($this->getJenkins());
        $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
      }
      $return = array(
        'status'  => 0,
        'message' => 'All delayed jobs have been launched',
      );
    }
    catch (Exception $e)
    {
      $return = array(
        'status'  => 1,
        'message' => $e->getMessage(),
      );
    }
    return $this->renderText(json_encode($return));
  }

}
