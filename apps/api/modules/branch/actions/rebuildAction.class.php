<?php

class rebuildAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfWebRequest $request
   *
   * @return array
   */
  public function getContent($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();

    $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);

    if (null === $groupRun)
    {
      throw new RuntimeException(sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));
    }

    $groupRun->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);
    JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());
    $message = $request->getParameter('delayed') == 1 ? 'added to delay list' : 'relaunched';

    return array(
      'message' => sprintf('The build [%s] has been %s', $groupRun->getLabel(), $message),
    );
  }

}
