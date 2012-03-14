<?php

/** @method myUser getUser() */
abstract class baseApiJenkinsAction extends commonJenkinsAction
{

  /**
   *
   */
  public function preExecute()
  {
    $this->forward404Unless(Configuration::get('api_enabled', false), 'Api is disabled');
    parent::preExecute();
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

}
