<?php

/**
 * @method sfPropelRoute getRoute
 */
class rebuildRunAction extends baseJenkinsAction
{

  /**
   * @param sfWebRequest $request
   */
  public function execute($request)
  {
    /** @var JenkinsRun $run */
    $run = $this->getRoute()->getObject();

    if (!$run->getLaunched())
    {
      // Job trouvé, actuellement en delayed => on le lance de suite
      $run->launchDelayed($this->getJenkins());

      $this->getUser()->setFlash('info', sprintf('The build [%s] has been launched and removed from the delayed list', $run->getJobName()));
    }
    else
    {
      $run->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);
      $run->computeJobBuildNumber($this->getJenkins(), $this->getUser());
      if ($request->getParameter('delayed') == 1)
      {
        $this->getUser()->setFlash('info', sprintf('The build [%s] has been added to the delayed list', $run->getJobName()));
      }
      else
      {
        $this->getUser()->setFlash('info', sprintf('The build [%s] has been relaunched', $run->getJobName()));
      }
    }
    
    $this->redirect($this->generateUrl('branch_view', $run->getJenkinsGroupRun()));
  }

}
