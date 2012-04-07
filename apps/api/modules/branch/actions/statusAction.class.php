<?php

class statusAction extends baseApiJenkinsAction
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

    $content  = array(
      'status' => null,
    );

    if (null !== $groupRun)
    {
      $content['status'] = $groupRun->getResult($this->getJenkins());
    }

    return $content;
  }

}
