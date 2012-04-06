<?php

abstract class baseBranchApiJenkinsAction extends baseApiJenkinsAction
{

  /**
   * retrieveJenkinsGroupRun
   *
   * @param mixed $request
   *
   * @return void
   */
  protected function retrieveJenkinsGroupRun($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();

    $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);

    if (null === $groupRun)
    {
      throw new RuntimeException(sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));
    }

    return $groupRun;
  }

}
