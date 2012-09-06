<?php

class rebuildBranchAction extends baseJenkinsAction
{

  /**
   * @param sfWebRequest $request
   *
   */
  public function execute($request)
  {
    $branchName = $request->getParameter('git_branch_slug');
    $userId     = $this->getUser()->getUserId();

    $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $branchName);
    $this->forward404If(null === $groupRun, sprintf('Can\'t retrieve JenkinsGroupRun with branch name %s and user id %s', $branchName, $userId));

//    $message = sprintf('The build [%s] has been relaunched', $groupRun->getLabel());
//    
//    if ($request->getParameter('delayed'))
//    {
//      $groupRun->rebuild($this->getJenkins(), true);
//      JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());
//      $message = sprintf('The jobs of build [%s] have been added to the delayed list', $groupRun->getLabel());
//    }
//    elseif ($request->getParameter('unstabled'))
//    {
//      
//    }
//    else
//    {
//      $groupRun->rebuild($this->getJenkins(), false);
//      JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());
//    }
    
    $delayed       = $request->getParameter('delayed') == 1;
    $justUnstabled = $request->getParameter('unstabled') == 1;
    $groupRun->rebuild($this->getJenkins(), $delayed, $justUnstabled);
    JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());
    
    $message = sprintf('The build [%s] has been relaunched', $groupRun->getLabel());
    if ($delayed)
    {
      $message = sprintf('The jobs of build [%s] have been added to the delayed list', $groupRun->getLabel());
    }
    elseif ($justUnstabled)
    {
      $message = sprintf('All the jobs of build [%s] have been added to the delayed list', $groupRun->getLabel());
    }
    
    $this->getUser()->setFlash('info', $message);

    $this->redirect($this->generateUrl('branch_view', $groupRun));
  }

}
