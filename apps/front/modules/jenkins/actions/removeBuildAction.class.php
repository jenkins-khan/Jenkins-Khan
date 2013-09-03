<?php
 
class removeBuildAction extends baseJenkinsAction
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
    /** @var JenkinsRun $run */
    $run = $this->getRoute()->getObject();
    $jenkinsGroupRun = $run->getJenkinsGroupRun();
    $run->delete();

    $this->getUser()->setFlash('info', sprintf('The build [%s] has been removed from the build branch [%s]', $run->getJobName(), $jenkinsGroupRun->getLabel()));
    $this->redirect($this->generateUrl('branch_view', $jenkinsGroupRun));
  }
}
