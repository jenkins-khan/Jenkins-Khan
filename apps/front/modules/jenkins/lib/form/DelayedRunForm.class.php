<?php

class DelayedRunForm extends sfForm
{

  /**
   * @param array $defaults
   * @param array $options
   * @param null $CSRFSecret
   */
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    foreach (array('runs') as $option)
    {
      if (!isset($options[$option]))
      {
        throw new Exception(sprintf('Option [%s] is required', $option));
      }
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }
  
  /**
   * 
   */
  public function configure()
  {
    $this->widgetSchema->setNameFormat('delayed_run[%s]'); 
    $widgets = array();
    $validators = array();
    foreach ($this->getRuns() as $run)
    {
      $widgets[$run->getId()] = new sfWidgetFormInputCheckbox(array('label' => $run->getJobName()));
      $validators[$run->getId()] = new sfValidatorString(array('required' => false));
    }
    
    $this->setWidget('runs', new sfWidgetFormSchema($widgets));
    $this->setValidator('runs', new sfValidatorSchema($validators));
  }
  
  /**
   * @return JenkinsRun[]
   */
  protected function getRuns()
  {
    return $this->getOption('runs');
  }


}
