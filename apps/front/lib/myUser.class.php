<?php

class myUser extends sfGuardSecurityUser
{
 
  /**
   * @return mixed
   */
  public function getJenkinsUrl()
  {
    return $this->getProfile()->getJenkinsUrl();
  }
  
}
