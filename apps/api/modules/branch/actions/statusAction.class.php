<?php

class statusAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return string
   */
  public function execute($request)
  {
    $branchName = $request->getParameter('branch_name');

    $userId   = $this->getGuardUser()->getUsername();
    $groupRun = JenkinsGroupRunPeer::retrieveByNaturalPk($userId, $branchName);
    if (null === $groupRun)
    {
      $status = 'UNTESTED';
    }
    else
    {
      $status = $groupRun->getResult($this->getJenkins());
    }

    $return = array(
      'status'=> $status,
    );
    return $this->renderText(json_encode($return));
  }

}