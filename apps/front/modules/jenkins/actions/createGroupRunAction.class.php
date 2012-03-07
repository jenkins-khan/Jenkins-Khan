<?php

class createGroupRunAction extends baseJenkinsAction
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
    
    $default = array(
      'user_id' => $this->getUser()->getUsername()
    );
    if ($request->hasParameter('from_group_run_id'))
    {
      //duplication de la configuration
      $groupRun = JenkinsGroupRunPeer::retrieveByPK($request->getParameter('from_group_run_id'));
      if (null !== $groupRun)
      {
        $default = $groupRun->buildDefaultFormValue($this->getJenkins(), $default);
      }
    }
    
    $form = new GroupRunForm($default, array('jenkins' => $this->getJenkins(), 'user_id' => $this->getUser()->getUsername()));
    
    if (sfRequest::POST === $request->getMethod())
    {
      $form->bind($request->getParameter('group_run'));

      if ($form->isValid())
      {
        $autoLaunch = 'on' === $form->getValue('auto_launch');
        
        //création du group run
        $runGroup = new JenkinsGroupRun();
        $runGroup->setUserId($this->getUser()->getUsername());
        $runGroup->setDate(new DateTime());
        $runGroup->setGitBranch($form->getValue('git_branch'));
        $runGroup->setLabel($form->getValue('label'));
        $runGroup->save();


        foreach ($form->getValue('builds') as $jobName => $jobInfo)
        {
          if (!$jobInfo['job_name'])
          {
            continue;
          }

          $parameters = array();
          if (isset($jobInfo['parameters']))
          {
            $parameters = $jobInfo['parameters'];
          }
         
          //créer les builds
          $run = new JenkinsRun();
          $run->setJenkinsGroupRun($runGroup);
          $run->setJobName($jobName);
          $run->setGitBranch($runGroup->getGitBranch());
          $run->encodeParameters($parameters);
          $run->setLaunched($autoLaunch);
          $run->save();

          if ($autoLaunch)
          {
            //launcher les builds
            $run->launch($this->getJenkins(), $parameters);
          }
        }

        $this->getUser()->setFlash('info', $autoLaunch ? "Jobs have been launched" : "Jobs have been registred in delayed list");
        $this->redirect(sprintf('jenkins/index?group_run_id=%s', $runGroup->getId()));
      }
    }
    
    $this->setVar('form', $form);
    $this->setVar('view_by_jobs', $this->buildViewByJobs($this->getJenkins()));
    $this->setVar('views', $this->getViews($this->getJenkins()));
    $this->setVar('default_active_view', 'default');
    
  }

  /**
   * @param Jenkins $jenkins
   * @return array
   */
  private function buildViewByJobs(Jenkins $jenkins)
  {
    $jobs = array();
    foreach ($jenkins->getViews() as $view)
    {
      $jenkinsView = $jenkins->getView($view['name']);

      foreach ($jenkinsView->getJobs() as $job)
      {
        $jobs[$job['name']][] = $view['name'];
      }
    }

    return $jobs;
  }
  
  /**
   * @param Jenkins $jenkins
   * @return array
   */
  private function getViews(Jenkins $jenkins)
  {
    $views = array();
    foreach ($jenkins->getViews() as $view)
    {
      $views[] = $view['name'];
    }
    
    return $views;
  }

}
