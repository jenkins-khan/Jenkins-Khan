<?php

/** @method myUser getUser() */
abstract class baseJenkinsAction extends sfAction
{

  /**
   * @var Jenkins
   */
  protected $jenkins;

  /**
   * @return void
   */
  public function preExecute()
  {
    if (null === $this->getJenkinsUrl())
    {
      $this->getUser()->setFlash('error', "There is no url for your Jenkins");
      $this->redirect('user/configure');
    }

    $jenkinsFactory = new Jenkins_Factory();
    $jenkins        = $jenkinsFactory->build($this->getJenkinsUrl());
    $this->setJenkins($jenkins);

    //à chaque hit on met à jour
    if ($jenkins->isAvailable())
    {
      JenkinsRunPeer::fillEmptyJobBuildNumber($jenkins, $this->getUser()->getUserId());
    }
    else
    {
      $this->getUser()->setFlash('error', 'Jenkins is not started');
    }

    $this->setVar('jenkins', $jenkins);
  }

  /**
   * @return \Jenkins
   */
  public function getJenkins()
  {
    return $this->jenkins;
  }

  /**
   * @param \Jenkins $jenkins
   *
   * @return $this
   */
  public function setJenkins(Jenkins $jenkins)
  {
    $this->jenkins = $jenkins;

    return $this;
  }

  /**
   * @return mixed
   */
  protected function getJenkinsUrl()
  {
    return $this->getUser()->getJenkinsUrl();
  }

  /**
   * @return mixed
   */
  protected function getUserName()
  {
    return $this->getUser()->getUsername();
  }

}
