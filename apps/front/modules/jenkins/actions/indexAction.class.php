<?php

class indexAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $userId  = $this->getUser()->getUserId();
    $jenkins = $this->getJenkins();
    
    $sortParams    = explode('_', $request->getParameter('sort'));
    $sortType      = 'none';
    $sortDirection = '';
    if (count($sortParams) == 1)
    {
      $sortType = (strlen($sortParams[0])) ? $sortParams[0] : 'none';
    }
    elseif (count($sortParams) == 2)
    {
      list($sortType, $sortDirection) = $sortParams;
    }

    if ($request->hasParameter('git_branch_slug'))
    {
      $groupRun   = JenkinsGroupRunPeer::retrieveBySfGuardUserIdAndGitBranchSlug($userId, $request->getParameter('git_branch_slug'));
      $this->forward404If(null === $groupRun, sprintf('Unable to find build branch with branch name %s ', $request->getParameter('git_branch_slug')));
      $currentGroupId = $groupRun->getId();
    }
    else
    {
      $currentGroupId = $request->getParameter('group_run_id');
    }

    $order = ($sortDirection == 'desc') ? Criteria::DESC : Criteria::ASC;
    
    $query = new JenkinsGroupRunQuery();
    $query->findBySfGuardUserId($userId);
    if ($sortType == 'date')
    {
      $query->orderByDate($order);
    }
    elseif ($sortType == 'label')
    {
      $query->orderByLabel($order);
    }
    
    $groupRuns = $query->find();

    $dataGroupRuns = array();
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
        'date'            => $groupRun->getDate('Y-m-d H:i:s'),
        'result'          => $groupRun->getResult($jenkins),
        'url_view'        => $this->generateUrl('branch_view', $groupRun) . '/sort/' . $sortType . '_' . $sortDirection,
      );
    }
    
    if ($sortType == 'result')
    {
      $method = ($sortDirection == 'asc') ? 'sortGroupRunsByResultAsc' : 'sortGroupRunsByResultDesc';
      uasort($dataGroupRuns, array($this, $method));
    }
    
    $currentGroupRun = JenkinsGroupRunPeer::retrieveByPK($currentGroupId);
    
    $sortMenu = array(
      'label'  => array(
        'label' => 'Name',
        'url'   => $this->generateUrl('branch_view', $currentGroupRun) . '/sort/label_' . $sortDirection,
      ),
      'date'   => array(
        'label' => 'Creation date',
        'url'   => $this->generateUrl('branch_view', $currentGroupRun) . '/sort/date_' . $sortDirection,
      ),
      'result' => array(
        'label' => 'Status',
        'url'   => $this->generateUrl('branch_view', $currentGroupRun) . '/sort/result_' . $sortDirection,
      ),
    );
    
    $this->setVar('group_runs', $dataGroupRuns);
    $this->setVar('current_group_run_id', $currentGroupId);
    $this->setVar('sort_type', $sortType);
    $this->setVar('sort_direction', $sortDirection);
    $this->setVar('sort_menu', $sortMenu);
    $this->setVar('branch_view_url', $this->generateUrl('branch_view', $currentGroupRun));
    $this->setVar('partial_url_for_sort_direction', sprintf('%s/sort/%s_', $this->generateUrl('branch_view', $currentGroupRun), $sortType));
  }
  
  /**
   * @param $a
   * @param $b
   * 
   * @return int
   */
  protected function sortGroupRunsByResultAsc($a, $b)
  {
    return strcmp($a['result'], $b['result']);
  }
  
  /**
   * @param $a
   * @param $b
   * 
   * @return int
   */
  protected function sortGroupRunsByResultDesc($a, $b)
  {
    return strcmp($b['result'], $a['result']);
  }

}
