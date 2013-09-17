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
      $launchedDelayed = $run->getLaunchDelayed();
      $attributesCheckbox = array();
      if ($launchedDelayed !== null)
      {
        $attributesCheckbox['checked'] = 'checked';
      }
      $widgets[$run->getId()] = new sfWidgetFormSchema(array(
        'launch_job' => new sfWidgetFormInputCheckbox(array('label' => $run->getJobName()), $attributesCheckbox),
        'scheduled_at' => new sfWidgetFormInputText(
          array('label' => 'Schedule at', 'default' => $launchedDelayed), 
          array('class' => 'timepicker')
        ),
      ));
      $validators[$run->getId()] = new sfValidatorSchema(array(
        'launch_job' => new sfValidatorString(array('required' => false)),
        'scheduled_at' => new sfValidatorDateTime(array(
          'min' => time(), 
          'required' => false,
          'date_format_range_error' => 'Y-m-d H:i'
        )),
      ));
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
