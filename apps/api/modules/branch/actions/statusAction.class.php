<?php

class statusAction extends baseBranchApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  protected function getContent($request)
  {
    $userId  = $this->getUser()->getUserId();
    $jenkins = $this->getJenkins();

    if ($jenkins->isAvailable())
    {
      JenkinsRunPeer::fillEmptyJobBuildNumber($jenkins, $userId);
    }

    $groupRun = $this->retrieveJenkinsGroupRun($request);

    $content  = array(
      'status' => null,
    );

    if (null !== $groupRun)
    {
      $content['status'] = $groupRun->getResult($jenkins);
    }

    return $content;
  }

}
