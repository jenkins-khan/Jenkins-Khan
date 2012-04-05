<?php

class deleteAction extends baseApiJenkinsAction
{
  /**
   * @param sfRequest $request
   * @return string
   */
  public function execute($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();
    
    try
    {
      $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);
      if (null === $groupRun)
      {
        throw new RuntimeException(sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));
      }
      
      // Suppression du groupe de runs, et, en cascade, des runs
      $groupRun->delete();
      
      $return = array(
        'status' => 0,
        'message' => sprintf('The build [%s] has been deleted', $groupRun->getLabel()),
      );
    }
    catch (Exception $e)
    {
      $return = array(
        'status' => 1,
        'message' => $e->getMessage(),
      );
    }
    return $this->renderText(json_encode($return));
  }
}
