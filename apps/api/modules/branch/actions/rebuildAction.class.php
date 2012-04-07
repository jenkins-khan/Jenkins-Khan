<?php

class rebuildAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfWebRequest $request
   *
   * @return array
   */
  public function getContent($request)
  {
    $groupRun = $this->getModelFactory()->getJenkinsGroupRun($request, $this->getUser());
    $groupRun->rebuild($this->getJenkins(), $request->getParameter('delayed') == 1);

    $message = $request->getParameter('delayed') == 1 ? 'added to delay list' : 'relaunched';

    return array(
      'message' => sprintf('The build [%s] has been %s', $groupRun->getLabel(), $message),
    );
  }

}
