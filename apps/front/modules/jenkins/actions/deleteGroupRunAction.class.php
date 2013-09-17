<?php
 
class deleteGroupRunAction extends baseJenkinsAction
{

  /**
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $this->forward404Unless($request->hasParameter('id'), 'id parameter is required');
    $jenkinsGroupRun = JenkinsGroupRunPeer::retrieveByPK($request->getParameter('id'));
    
    $this->forward404Unless(
      $jenkinsGroupRun instanceOf JenkinsGroupRun, 
      sprintf('Can\'t create JenkinsGroupRun with id %s', $request->getParameter('id'))
    );
    
    //suppression du group run (et, en cascade, des runs)
    $jenkinsGroupRun->delete(null);
    
    $this->getUser()->setFlash('info', sprintf('Build branch [%s] has been deleted', $jenkinsGroupRun->getLabel()));
    
    $this->redirect('jenkins/index');
  }
}
