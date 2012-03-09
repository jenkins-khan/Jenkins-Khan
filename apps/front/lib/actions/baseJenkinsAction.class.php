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
    if (null === $this->getUser()->getJenkinsUrl())
    {
      $this->getUser()->setFlash('error', "There is no url for your Jenkins");
      $this->redirect('user/configure');
    }

    $jenkinsFactory = new Jenkins_Factory();
    $jenkins        = $jenkinsFactory->build($this->getUser()->getProfile()->getJenkinsUrl());
    $this->setJenkins($jenkins);

    //à chaque hit on met à jour
    if ($jenkins->isAvailable())
    {
      JenkinsRunPeer::fillEmptyJobBuildNumber($jenkins, $this->getUser());
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
   */
  public function setJenkins(Jenkins $jenkins)
  {
    $this->jenkins = $jenkins;

    return $this;
  }

}
