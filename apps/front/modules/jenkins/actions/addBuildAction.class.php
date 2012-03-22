<?php
 
class addBuildAction extends baseJenkinsAction
{

  /**
   * Execute any application/business logic for this component.
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    //pas de jenkins => pas de form
    if (!$this->getJenkins()->isAvailable())
    {
      $this->redirect('jenkins/index');
    }

    if (is_array($buildRequest = $request->getParameter('build')))
    {
      $groupRunId = isset($buildRequest['group_run_id']) ? $buildRequest['group_run_id'] : null;
    }
    else
    {
      $groupRunId = $request->getParameter('group_run_id');
    }
    
    $this->forward404If($groupRunId === null, 'group_run_id parameter is required');
    $jenkinsGroupRun = JenkinsGroupRunPeer::retrieveByPK($groupRunId);

    $this->forward404Unless(
      $jenkinsGroupRun instanceOf JenkinsGroupRun,
      sprintf('Can\'t create JenkinsGroupRun with id %s', $groupRunId)
    );

    $defaults = array();
    if ($request->hasParameter('auto_launch'))
    {
      $autoLaunch = $request->getParameter('auto_launch');
      if ('on' === $autoLaunch)
      {
        $defaults = array('auto_launch' => true);
      }
    }
    else 
    {
      $defaults = array('auto_launch' => true);
    }
    
    
    $form = new BuildForm($defaults, array(
      'jenkins'     => $this->getJenkins(),
      'group_run'   => $jenkinsGroupRun
    ));

    if (sfRequest::POST === $request->getMethod())
    {
      $form->bind($buildRequest);
      if ($form->isValid())
      {
        $jobName = $form->getValue('job');
        $autoLaunch = 'on' === $form->getValue('auto_launch');

        $extraParameters = $form->getValue('parameters');
        $jobParameters   = array();

        if (isset($extraParameters[$jobName]))
        {
          $jobParameters = $extraParameters[$jobName];
        }
        
        //créer les builds
        $run = new JenkinsRun();
        $run->setJenkinsGroupRun($jenkinsGroupRun);
        $run->setJobName($jobName);
        $run->encodeParameters($jobParameters);
        $run->setLaunched($autoLaunch);
        $run->save();
        
        //launcher les builds
        if ($autoLaunch)
        {
          $run->launch($this->getJenkins(), $jobParameters);
          $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
        }

        $this->getUser()->setFlash('info', sprintf('Build [%s] has been added to build branch [%s]', $run->getJobName(), $jenkinsGroupRun->getLabel()));

        if ($request->hasParameter('add_and_continue'))
        {
          $urlRedirect = sprintf('jenkins/addBuild?auto_launch=%s&group_run_id=%s', $autoLaunch ? 'on' : 'off', $jenkinsGroupRun->getId());
        }
        else
        {
          $urlRedirect = $this->generateUrl('branch_view', $jenkinsGroupRun);
        }
        $this->redirect($urlRedirect);
      }
    }
    
    
    $this->setVar('form', $form);
    $this->setVar('group_run', array(
      'label'      => $jenkinsGroupRun->getLabel(),
      'git_branch' => $jenkinsGroupRun->getGitBranch(),
      'result'     => $jenkinsGroupRun->getResult($this->getJenkins()),
    ));
  }
}
