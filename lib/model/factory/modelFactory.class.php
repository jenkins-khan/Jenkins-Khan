<?php

class modelFactory
{

  /**
   * @param sfRequest       $request
   * @param jenkinsKhanUser $user
   *
   * @return JenkinsGroupRun
   * @throws RuntimeException
   */
  public function getJenkinsGroupRun(sfRequest $request, jenkinsKhanUser $user)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $user->getUserId();

    $groupRun = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);

    if (null === $groupRun)
    {
      throw new RuntimeException(sprintf(
        'Can\'t retrieve JenkinsGroupRun with branch name %s for user %s.', 
        $branchName, 
        $user->getUsername()
      ));
    }

    return $groupRun;
  }

  /**
   * @param jenkinsKhanUser $user
   *
   * @return JenkinsRun[]
   */
  public function getDelayedRuns(jenkinsKhanUser $user)
  {
    return JenkinsRunPeer::getDelayed($user);
  }

}
