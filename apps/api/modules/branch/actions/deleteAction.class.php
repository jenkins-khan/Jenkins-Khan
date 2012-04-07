<?php

class deleteAction extends baseApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  protected function getContent($request)
  {
    $groupRun = $this->getModelFactory()->getJenkinsGroupRun($request, $this->getUser());

    // Suppression du groupe de runs, et, en cascade, des runs
    $groupRun->delete();

    return array(
      'message' => sprintf('The build [%s] has been deleted', $groupRun->getLabel()),
    );
  }

}
