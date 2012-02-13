<?php

class BuildForm extends sfForm
{

  /**
   * @param array $defaults
   * @param array $options
   * @param null $CSRFSecret
   */
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    foreach (array('jenkins', 'group_run') as $option)
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

    if (!$options['group_run'] instanceof JenkinsGroupRun)
    {
      throw new Exception('Option [group_run] must be a JenkinsGroupRun object');
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  /**
   * @return void
   */
  public function configure()
  {
    $this->widgetSchema->setNameFormat('build[%s]');

    $allJobs     = $this->getJenkins()->getAllJobs();
    $jobsName    = array();
    $jobsInGroup = $this->getJobsInGroupRun();

    $parametersWidget    = array();
    $parametersValidator = array();
    
    foreach ($allJobs as $jobInfos)
    {
      $jobName = $jobInfos['name'];
      $parameters = $this->getJenkins()->getJob($jobName)->getParametersDefinition();
      unset($parameters[Jenkins_Job::BRANCH_PARAMETER_NAME]);

      if (count($parameters))
      {
        //des parametres, on s'occupe du widget
        $jobsName[] = $jobName;
        list($widget, $validator) = $this->createBuildWidgetValidator($parameters);

        $parametersWidget[$jobName]    = $widget;
        $parametersValidator[$jobName] = $validator;
      }
      else
      {
        //pas de parametres, on les élimine si dejà lancé
        if (!isset($jobsInGroup[$jobName]))
        {
          $jobsName[] = $jobName;
        }
      }
    }

    $this->setWidget('group_run_id', new sfWidgetFormInputHidden(array(
      'default' => $this->getJenkinsGroupRun()->getId()
    )));
    $this->setValidator('group_run_id', new sfValidatorInteger(array('required' => true)));

    $this->setWidget('auto_launch', new sfWidgetFormInputCheckbox(array('label' => 'Launch automatically')));
    $this->setValidator('auto_launch', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('job', new sfWidgetFormChoice(array('choices' => $this->combine($jobsName))));
    $this->setValidator('job', new sfValidatorChoice(array('choices' => $jobsName)));

    $this->setWidget('parameters', new sfWidgetFormSchema($parametersWidget));
    $this->setValidator('parameters', new sfValidatorSchema($parametersValidator));
  }

  /**
   * Available jobs
   *
   * @return array
   */
  private function getJobsInGroupRun()
  {
    $jobs = array();

    foreach ($this->getJenkinsGroupRun()->getJenkinsRuns() as $run)
    {
      /** @var JenkinsRun $run */
      $jobs[$run->getJobName()] = $run->getJobName();
    }

    return $jobs;
  }

  /**
   * @param string $jobName
   * @return array
   */
  private function createBuildWidgetValidator($parameters)
  {
    $extraParametersWidgets    = array();
    $extraParametersValidators = array();
    foreach ($parameters as $name => $parameter)
    {
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

    $widget    = new sfWidgetFormSchema($extraParametersWidgets, array('label' => 'Parameters'));
    $validator = new sfValidatorSchema($extraParametersValidators);

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
   * @return JenkinsGroupRun
   */
  public function getJenkinsGroupRun()
  {
    return $this->getOption('group_run');
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
