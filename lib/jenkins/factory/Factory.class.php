<?php
 
class Jenkins_Factory 
{

  /**
   * @param myUser $user
   * @return Jenkins
   */
  public function build($url)
  {
    return new Jenkins($url);
  }


}
