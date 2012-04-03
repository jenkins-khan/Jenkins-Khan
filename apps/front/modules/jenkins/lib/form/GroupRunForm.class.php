<?php

class GroupRunForm extends sfForm
{

  /**
   * @param array $defaults
   * @param array $options
   * @param null $CSRFSecret
   */
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    foreach (array('jenkins', 'sf_guard_user_id') as $option)
    {
      if (!isset($options[$option]))
      {
        throw new Exception(sprintf('Option [%s] is required', $option));
      }
    }

    if (!$options['jenkins'] instanceof Jenkins)
    {
      throw new Exception('Option [jenkins] must be a Jenkins object');
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  /**
   * @return void
   */
  public function configure()
  {
    $this->widgetSchema->setNameFormat('group_run[%s]');

    $allJobs = $this->getJenkins()->getAllJobs();
    $jobsName = array_keys($allJobs);

    $this->setWidget('sf_guard_user_id', new sfWidgetFormInputHidden());
    $this->setWidget('label', new sfWidgetFormInputText(array('label' => 'Build branch name')));
    $this->setWidget('git_branch', new sfWidgetFormInputText(array('label' => 'Git branch')));
    $this->setWidget('auto_launch', new sfWidgetFormInputCheckbox(array('label' => 'Launch automatically', 'default' => true )));

    $this->setValidator('sf_guard_user_id', new sfValidatorString(array()));
    $this->setValidator('label', new sfValidatorString(array('max_length' => 100)));
    $this->setValidator('git_branch', new sfValidatorString(array('max_length' => 100)));
    $this->setValidator('auto_launch', new sfValidatorString(array('required' => false)));

    $widgets    = array();
    $validators = array();
    foreach ($jobsName as $jobName)
    {
      list($widgets[$jobName], $validators[$jobName]) = $this->createBuildWidgetValidator($jobName);
    }

    $this->setWidget('builds', new sfWidgetFormSchema($widgets));
    $this->setValidator('builds', new sfValidatorSchema($validators));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(
        array(
          'model' => 'JenkinsGroupRun',
          'column' => array('sf_guard_user_id', 'git_branch'),
        ),
        array(
          'invalid' => 'You already have an existing build branch for this git branch'
        )
      )
    );

  }

  /**
   * @param string $jobName
   * @return array
   */
  private function createBuildWidgetValidator($jobName)
  {
    $extraParametersWidgets    = array();
    $extraParametersValidators = array();
    foreach ($this->getJenkins()->getJob($jobName)->getParametersDefinition() as $name => $parameter)
    {
      if (in_array($name, array(Jenkins_Job::BRANCH_PARAMETER_NAME)))
      {
        continue;
      }

      if (is_array($parameter['choices']))
      {
        $extraParametersWidgets[$name]    = new sfWidgetFormChoice(
          array(
            'choices' => $this->combine($parameter['choices']),
            'default' => $parameter['default'],
          ),
          array('label' => strlen($parameter['description']) > 0 ? $parameter['description'] : $name)
        );

        $extraParametersValidators[$name] = new sfValidatorChoice(
          array('choices' => $parameter['choices'])
        );
      }
      else
      {
        $extraParametersWidgets[$name]    = new sfWidgetFormInput(
          array(
            'default' => $parameter['default'],
          ),
          array('label' => strlen($parameter['description']) > 0 ? $parameter['description'] : $name)
        );

        $extraParametersValidators[$name] = new sfValidatorString(array('required' => false));
      }
    }

    $widgets = array(
      'job_name' => new sfWidgetFormInputCheckbox(array('label' => $jobName), array('label' => ''))
    );
    $validators = array(
      'job_name' => new sfValidatorBoolean(array('required' => false))
    );

    if (count($extraParametersWidgets) > 0)
    {
      $widgets['parameters']    = new sfWidgetFormSchema($extraParametersWidgets, array('label' => 'Parameters'));
      $validators['parameters'] = new sfValidatorSchema($extraParametersValidators);
      $widgets['parameters']->setFormFormatterName('list');
    }

    $widget    = new sfWidgetFormSchema($widgets);
    $validator = new sfValidatorSchema($validators);

    return array($widget, $validator);
  }


  /**
   * @return Jenkins
   */
  public function getJenkins()
  {
    return $this->getOption('jenkins');
  }

  /**
   * @param array $array
   * @return array
   */
  private function combine($array)
  {
    return array_combine($array, $array);
  }
}
