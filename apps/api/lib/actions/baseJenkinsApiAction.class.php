<?php

/** @method myUser getUser() */
abstract class baseApiJenkinsAction extends sfAction
{

  /**
   * @var Jenkins
   */
  protected $jenkins;

  /**
   *
   */
  public function preExecute()
  {
    $this->forward404Unless(Configuration::get('api_enabled', false), 'Api is disabled');
    $jenkinsFactory = new Jenkins_Factory();
    $jenkins        = $jenkinsFactory->build($this->getJenkinsUrl());
    $this->setJenkins($jenkins);
  }

  /**
   * @return string
   */
  protected function getJenkinsUrl()
  {
    return $this->getGuardUser()->getProfile()->getJenkinsUrl();
  }

  /**
   * @return sfGuardUser
   *
   * @throws sfException
   */
  protected function getGuardUser()
  {
    $guardUser = sfGuardUserPeer::retrieveByUsername($this->getUserName());
    if (null === $guardUser)
    {
      throw new sfException(sprintf('user "%s" not found', $username));
    }
    return $guardUser;
  }

  /**
   * @return string
   */
  protected function getUserName()
  {
    return $this->getRequest()->getParameter('username');
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

}
