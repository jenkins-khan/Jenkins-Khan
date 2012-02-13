<?php

class configureAction extends sfAction
{

  /**
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  public function execute($request)
  {
    $form = new ConfigureUserProfileForm($this->getUser()->getProfile());

    if (sfRequest::PUT === $request->getMethod())
    {
      $form->bind($request->getParameter('profile'));

      if ($form->isValid())
      {
        $form->save();
      }
    }
    
    $this->setVar('form', $form);
  }
}
