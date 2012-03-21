<?php

class apiKeyGenerateAction extends sfAction
{

  public function execute($request)
  {
    $this->forward404Unless(Configuration::get('api_enabled', false), 'Api is disabled');

    $profile = $this->getUser()->getProfile();
    $profile->setApiKey(md5(microtime()));
    $profile->save();

    $this->getUser()->setFlash('info', 'Your api key has been generated');
    $this->redirect('user/configure');
  }

}
