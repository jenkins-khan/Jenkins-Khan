<?php
 
class Jenkins_Build 
{
  /**
   * @var string
   */
  const FAILURE = 'FAILURE';  
  
  /**
   * @var string
   */
  const SUCCESS = 'SUCCESS';
  
  /**
   * @var string
   */
  const RUNNING = 'RUNNING';
  
  /**
   * @var string
   */
  const WAITING = 'WAITING';  
  
  /**
   * @var string
   */
  const UNSTABLE = 'UNSTABLE';

  /**
   * @var string
   */
  const ABORTED = 'ABORTED';
  
  /**
   * @var array 
   */
  private $build;
  
  /**
   * @var Jenkins
   */
  private $jenkins;
  
  
  /**
   * @param array $build
   */
  public function __construct($build, Jenkins $jenkins)
  {
    $this->build = $build;
    $this->setJenkins($jenkins);
  }
  
  /**
   * @return array
   */
  public function getInputParameters()
  {
    $parameters = array();
    
    if (!property_exists($this->build->actions[0], 'parameters'))
    {
      return $parameters;
    }
    
    foreach ($this->build->actions[0]->parameters as $parameter)
    {
      $parameters[$parameter->name] = $parameter->value;
    }
    
    return $parameters;
  }
  
  /**
   * @return int
   */
  public function getTimestamp()
  {
    //division par 1000 => pas de millisecondes
    return $this->build->timestamp / 1000;
  }


  /**
   * @return int
   */
  public function getDuration()
  {
    //division par 1000 => pas de millisecondes
    return $this->build->duration / 1000;
  }
  
  /**
   * @return int
   */
  public function getNumber()
  {
    return $this->build->number;
  }
  
  /**
   * @return null
   */
  public function getProgress()
  {
    return property_exists($this->build, 'progress') ? $this->build->progress : null;
  }
  
  /**
   * @return null|string
   */
  public function getResult()
  {
    $result = null;
    switch($this->build->result)
    {
      case 'FAILURE':
        $result = Jenkins_Build::FAILURE;
        break;      
      case 'SUCCESS':
        $result = Jenkins_Build::SUCCESS;
        break;      
      case 'UNSTABLE':
        $result = Jenkins_Build::UNSTABLE;
        break;       
      case 'ABORTED':
        $result = Jenkins_Build::ABORTED;
        break; 
      case 'WAITING':
        $result = Jenkins_Build::WAITING;
        break;
      default:
        $result = Jenkins_Build::RUNNING;
        break;
    }
    
    return $result;
  }
  
  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->build->url;
  }
  
  /**
   * @return Jenkins_Executor|null
   */
  public function getExecutor()
  {
    if (!$this->isRunning())
    {
      return null;
    }
    
    $runExecutor = null;
    foreach ($this->getJenkins()->getExecutors() as $executor)
    {
      /** @var Jenkins_Executor $executor */
      
      if ($this->getUrl() === $executor->getBuildUrl())
      {
        $runExecutor = $executor;
      }
    }
    
    return $runExecutor;
  }
  
  /**
   * @return bool
   */
  public function isRunning()
  {
    return Jenkins_Build::RUNNING === $this->getResult();
  }

  /**
   * @return \Jenkins
   */
  public function getJenkins()
  {
    return $this->jenkins;
  }

  /**
   * @param \Jenkins $jenkins
   *
   * @return Jenkins_Job
   */
  public function setJenkins(Jenkins $jenkins)
  {
    $this->jenkins = $jenkins;

    return $this;
  }
}
