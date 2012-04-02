<?php

class indexAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $userId   = $this->getUser()->getUserId();
    $sortType = $request->getParameter('sort');
    $jenkins  = $this->getJenkins();

    if ($request->hasParameter('git_branch_slug'))
    {
      $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $request->getParameter('git_branch_slug'));
      $currentGroupId = $groupRun->getId();
    }
    else
    {
      $currentGroupId   = $request->getParameter('group_run_id');
    }
    
    $criteriaGroupRun = new Criteria();
    $criteriaGroupRun->add(JenkinsGroupRunPeer::SF_GUARD_USER_ID, $userId, Criteria::EQUAL);
    if ($sortType == 'date')
    {
      $criteriaGroupRun->addDescendingOrderByColumn(JenkinsGroupRunPeer::DATE);
    }
    elseif ($sortType == 'label')
    {
      $criteriaGroupRun->addDescendingOrderByColumn(JenkinsGroupRunPeer::LABEL);
    }

    $dataGroupRuns = array();
    $groupRuns     = JenkinsGroupRunPeer::doSelect($criteriaGroupRun);
    foreach ($groupRuns as $groupRun)
    {
      if (null === $currentGroupId)
      {
        $currentGroupId = $groupRun->getId();
      }
      
      /** @var JenkinsGroupRun $groupRun */
      $dataGroupRuns[$groupRun->getId()] = array(
        'label'           => $groupRun->getLabel(),
        'git_branch'      => $groupRun->getGitBranch(),
        'git_branch_slug' => $groupRun->getGitBranchSlug(),
        'date'            => $groupRun->getDate('d/m/Y H:i:s'),
        'result'          => $groupRun->getResult($jenkins),
        'url_view'        => $this->generateUrl('branch_view', $groupRun),
      );
    }
    
    if ($sortType == 'result')
    {
      uasort($dataGroupRuns, array($this, 'sortGroupRunsByResult'));
    }
    
    $sortMenu = array(
      'label'  => 'Label',
      'date'   => 'Creation date',
      'result' => 'Status',
      'none'   => 'None',
    );
    
    $this->setVar('group_runs', $dataGroupRuns);
    $this->setVar('current_group_run_id', $currentGroupId);
    $this->setVar('sort_type', $sortType);
    $this->setVar('sort_menu', $sortMenu);
  }
  
  /**
   * @param $a
   * @param $b
   * 
   * @return int
   */
  protected function sortGroupRunsByResult($a, $b)
  {
    return strcmp($a['result'], $b['result']);
  }

}
