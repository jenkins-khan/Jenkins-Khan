<?php

class deleteAction extends baseApiJenkinsAction
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

    $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);
    if (null === $groupRun)
    {
      throw new RuntimeException(sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));
    }

    // Suppression du groupe de runs, et, en cascade, des runs
    $groupRun->delete();

    return array(
      'message' => sprintf('The build [%s] has been deleted', $groupRun->getLabel()),
    );
  }

}
