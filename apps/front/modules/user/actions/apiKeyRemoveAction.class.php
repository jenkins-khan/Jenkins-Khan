<?php

class apiKeyRemoveAction extends sfAction
{

  public function execute($request)
  {
    $this->forward404Unless(Configuration::get('api_enabled', false), 'Api is disabled');

    $profile = $this->getUser()->getProfile();

    $profile->setApiKey(null);
    $profile->save();

    $this->getUser()->setFlash('notice', 'Api Key removed');
    $this->redirect('user/configure');
  }

}
