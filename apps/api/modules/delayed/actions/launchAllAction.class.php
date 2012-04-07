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
    $runs = $this->getModelFactory()->getDelayedRuns($this->getUser());

    foreach ($runs as $run)
    {
      $run->launchDelayed($this->getJenkins());
    }

    return array(
      'message' => 'All delayed jobs have been launched',
    );
  }

}
