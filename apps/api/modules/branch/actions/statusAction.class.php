<?php

class statusAction extends baseApiJenkinsAction
{

  /**
   * 
   */
  public function preExecute()
  {
    parent::preExecute();
    $userId  = $this->getUser()->getUserId();
    $jenkins = $this->getJenkins();

    if ($jenkins->isAvailable())
    {
      JenkinsRunPeer::fillEmptyJobBuildNumber($jenkins, $userId);
    }

  }


  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  protected function getContent($request)
  {
    $groupRun = $this->getModelFactory()->getJenkinsGroupRun($request, $this->getUser());

    $content  = array(
      'status' => null,
    );

    if (null !== $groupRun)
    {
      $content['status'] = $groupRun->getResult($this->getJenkins());
    }

    return $content;
  }

}
