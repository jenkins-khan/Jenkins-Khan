<?php
 
class rebuildAction extends baseJenkinsAction
{

  /**
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $this->forward404Unless($request->hasParameter('run_id'), 'run_id parameter is required');
    $jenkinsRun = JenkinsRunPeer::retrieveByPK($request->getParameter('run_id'));

    $this->forward404Unless(
      $jenkinsRun instanceOf JenkinsRun,
      sprintf('Can\'t create JenkinsRun with id %s', $request->getParameter('run_id'))
    );
    
    $jenkins         = $this->getJenkins();
    $build           = $jenkinsRun->getJenkinsBuild($jenkins);
    
    $inputParameters = array();
    if (null !== $build)
    {
      //peu importe ce qui est stocké dans la base => Jenkins fait toujours foi
      $inputParameters = $build->getInputParameters();
    }
    
    //désactivation du numéro de run
    $jenkinsRun->setJobBuildNumber(null);
    $jenkinsRun->save();

    $jenkins->launchJob($jenkinsRun->getJobName(), $inputParameters);

    $this->getUser()->setFlash('notice', sprintf('Build [%s] has been relaunched', $jenkinsRun->getJobName()));

    $this->redirect(sprintf('jenkins/index?group_run_id=%s', $jenkinsRun->getJenkinsGroupRunId()));
  }
}
