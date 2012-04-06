<?php

class deleteAction extends baseBranchApiJenkinsAction
{

  /**
   *
   * @param sfRequest $request
   *
   * @return array
   */
  protected function getContent($request)
  {

    $groupRun = $this->retrieveJenkinsGroupRun($request);

    // Suppression du groupe de runs, et, en cascade, des runs
    $groupRun->delete();

    return array(
      'message' => sprintf('The build [%s] has been deleted', $groupRun->getLabel()),
    );
  }

}
