<?php

class rebuildAction extends baseBranchApiJenkinsAction
{

  /**
   *
   * @param sfWebRequest $request
   *
   * @return array
   */
  public function getContent($request)
  {
    $groupRun = $this->retrieveJenkinsGroupRun($request);

    $groupRun->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);
    JenkinsRunPeer::fillEmptyJobBuildNumber($this->getJenkins(), $this->getUser()->getUserId());
    $message = $request->getParameter('delayed') == 1 ? 'added to delay list' : 'relaunched';

    return array(
      'message' => sprintf('The build [%s] has been %s', $groupRun->getLabel(), $message),
    );
  }

}
