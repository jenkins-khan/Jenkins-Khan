<?php

/** @method myUser getUser() */
abstract class baseJenkinsAction extends commonJenkinsAction
{

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
