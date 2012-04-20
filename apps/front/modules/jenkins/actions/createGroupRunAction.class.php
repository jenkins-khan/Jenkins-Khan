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
      'sf_guard_user_id' => $this->getUser()->getUserId()
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

    if ($request->hasParameter('branch'))
    {
      $branch = $request->getParameter('branch');
      $default['git_branch'] = $branch;
      $default['label']      = $branch;
    }
    
    $form = new GroupRunForm($default, array('jenkins' => $this->getJenkins(), 'sf_guard_user_id' => $this->getUser()->getUserId()));

    if (sfRequest::POST === $request->getMethod())
    {
      $form->bind($request->getParameter('group_run'));

      if ($form->isValid())
      {
        $autoLaunch = 'on' === $form->getValue('auto_launch');

        //création du group run
        $runGroup = new JenkinsGroupRun();
        $runGroup->setSfGuardUserId($this->getUser()->getUserId());
        $runGroup->setDate(new DateTime());
        $runGroup->setGitBranch($form->getValue('git_branch'));
        $runGroup->setLabel($form->getValue('label'));
        $runGroup->save();

        $nbJobs = 0;
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
          $run->encodeParameters($parameters);
          $run->setLaunched($autoLaunch);
          $run->save();

          $nbJobs++;
          if ($autoLaunch)
          {
            //launcher les builds
            $run->launch($this->getJenkins(), $parameters);
            $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
          }
        }

        if (0 === $nbJobs)
        {
          $this->getUser()->setFlash('info', sprintf('Build branch [%s] has been created', $runGroup->getLabel()));
        }
        else 
        {
          $label = 1 === $nbJobs ? 'Job has ' : 'Jobs have ';
          $this->getUser()->setFlash('info', $label . ($autoLaunch ? "been launched" : "been registred in delayed list"));
        }
        $this->redirect($this->generateUrl('branch_view', $runGroup));
      }
    }

    $views = $this->getViews($this->getJenkins());
    $defaultView = $this->getJenkins()->getPrimaryView();

    $this->setVar('form', $form);
    $this->setVar('view_by_jobs', $this->buildViewByJobs($this->getJenkins()));
    $this->setVar('views', $views);
    $this->setVar('default_active_view', null === $defaultView ? null : $defaultView->getName());
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
      /** @var Jenkins_View $view */
      /** @var Jenkins_Job $job */
      foreach ($view->getJobs() as $job)
      {
        $jobs[$job->getName()][] = $view->getName();
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
      /** @var Jenkins_View $view */
      $views[] = $view->getName();
    }

    return $views;
  }

}
