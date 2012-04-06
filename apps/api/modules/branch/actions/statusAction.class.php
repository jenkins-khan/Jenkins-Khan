<?php

class statusAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  protected function getContent($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();
    $jenkins    = $this->getJenkins();

    if ($jenkins->isAvailable())
    {
      JenkinsRunPeer::fillEmptyJobBuildNumber($jenkins, $userId);
    }

    $groupRun = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);

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
