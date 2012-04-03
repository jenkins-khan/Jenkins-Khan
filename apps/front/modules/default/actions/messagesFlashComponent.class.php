<?php
 
class messagesFlashComponent extends sfComponent
{

  /**
   * Execute any application/business logic for this component.
   *
   * @param sfRequest $request The current sfRequest object
   *
   * @return mixed     A string containing the view name associated with this action
   */
  function execute($request)
  {
    $messagesByLevel = array();
    foreach (array('info', 'notice', 'warning', 'error') as $level)
    {
      if (!$this->getUser()->hasFlash($level))
      {
        continue;
      }
      
      $messages = $this->getUser()->getFlash($level);
      $messagesByLevel[$level] = is_array($messages) ? $messages : array($messages);
    }
    
    $this->setVar('messages_by_level', $messagesByLevel);
  }
}
