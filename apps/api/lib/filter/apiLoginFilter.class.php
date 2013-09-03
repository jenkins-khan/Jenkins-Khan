<?php

class apiLoginFilter extends sfBasicSecurityFilter
{

  /**
   * @see sfFilter
   */
  public function execute($filterChain)
  {
    if ($this->isFirstCall())
    {
      $apiKey  = $this->getContext()->getRequest()->getParameter('apikey');
      $profile = ProfilePeer::retrieveByApiKey($apiKey);

      if (null !== $profile)
      {
        $this->context->getUser()->signIn($profile->getSfGuardUser());
      }
      else
      {
        throw new RuntimeException('Api key is not authorized');
      }
    }

    parent::execute($filterChain);
  }

}
