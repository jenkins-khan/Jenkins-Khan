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

    $userId   = $this->getGuardUser()->getId();
    $groupRun = JenkinsGroupRunPeer::retrieveByNaturalPk($userId, $branchName);

    $status = null;
    if (null !== $groupRun)
    {
      $status = $groupRun->getResult($this->getJenkins());
    }

    $return = array(
      'status'=> $status,
    );
    return $this->renderText(json_encode($return));
  }

}