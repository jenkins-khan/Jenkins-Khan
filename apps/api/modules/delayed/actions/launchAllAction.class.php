<?php

class launchAllAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  public function getContent($request)
  {
    $runs = JenkinsRunPeer::getDelayed($this->getUser());

    foreach ($runs as $run)
    {
      $run->launchDelayed($this->getJenkins());
    }

    return array(
      'message' => 'All delayed jobs have been launched',
    );
  }

}
