<?php

class delayedAction extends baseJenkinsAction
{

  /**
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $runs = JenkinsRunPeer::getDelayed($this->getUser());
    $form = new DelayedRunForm(array(), array('runs' => $runs));
    $jenkins = $this->getJenkins();

    if (sfRequest::POST === $request->getMethod())
    {
      $form->bind($request->getParameter('delayed_run'));
      if ($form->isValid())
      {
        $messages = array();
        foreach ($form->getValue('runs') as $id => $datas)
        {
          $run = JenkinsRunPeer::retrieveByPK($id);
          if ('on' !== $datas['launch_job'])
          {
            $run->setLaunchDelayed(null);
            $run->save();
            continue;
          }

          $launchAt = null;
          if (strlen($datas['scheduled_at']) > 0)
          {
            $launchAt = strtotime($datas['scheduled_at']);
          }
          
          if (null === $launchAt)
          {
            $run->launchDelayed($this->getJenkins());
            $messages[] = sprintf('The job [%s] in build branch has been launched', $run->getJobName(), $run->getGitBranch());
          }
          else
          {
            $run->setLaunchDelayed($launchAt);
            $run->save();
            $messages[] = sprintf(
              'The job [%s] in build %s branch will be launched at %s ', 
              $run->getJobName(), 
              $run->getGitBranch(), 
              $run->getLaunchDelayed('Y-m-d H:i')
            );
          }
        }
        $this->getUser()->setFlash('info', $messages);
        $this->redirect('jenkins/index');
      }
    }
    
    $delayedRuns       = array();
    $durationFormatter = new durationFormatter();
    foreach ($runs as $run)
    {
      $groupRun   = $run->getJenkinsGroupRun();
      $parameters = $run->getParameters();
      $build      = $run->getJenkinsJob($jenkins)->getLastSuccessfulBuild($jenkins);
      $lastDuration   = 0;
      if (null !== $build)
      {
        $lastDuration   = $build->getDuration();
      }
      
      $delayedRuns[$run->getId()] = array(
        'group_run_label'  => $groupRun->getLabel(),
        'group_run_result' => $groupRun->getResult($jenkins),
        'last_duration'    => $durationFormatter->formatte($lastDuration),
        'parameters'       => null === $parameters ? $parameters : json_decode($run->getParameters(), true),
      );
    }
    
    $this->setVar('form', $form);
    $this->setVar('delayed_runs', $delayedRuns);
  }
}
