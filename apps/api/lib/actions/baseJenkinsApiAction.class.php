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
   */
  protected function getGuardUser()
  {
    $apiKey  = $this->getRequest()->getParameter('apikey');
    $profile = ProfilePeer::retrieveByApiKey($apiKey);
    $this->forward404Unless(null !== $profile, 'Unauthorized api key');
    return $profile->getSfGuardUser();
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
