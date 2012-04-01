<?php

class jenkinsKhanUser extends sfGuardSecurityUser
{

  /**
   * @return string
   */
  public function getJenkinsUrl()
  {
    return $this->getProfile()->getJenkinsUrl();
  }

  /**
   * @return int
   */
  public function getUserId()
  {
    return $this->getProfile()->getSfGuardUserId();
  }

}
