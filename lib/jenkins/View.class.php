<?php

class Jenkins_View
{
  /**
   * @var array
   */
  private $view;

  /**
   * @var Jenkins
   */
  protected $jenkins;


  /**
   * @param array    $job
   * @param \Jenkins $jenkins
   */
  public function __construct($view, Jenkins $jenkins)
  {
    $this->view = $view;
    $this->jenkins = $jenkins;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->view->name;
  }
  
  /**
   * @return array
   */
  public function getJobs()
  {
    $jobs = array();
    
    foreach ($this->view->jobs as $job)
    {
      $jobs[] = array(
        'name' => $job->name
      );
    }
    
    return $jobs;
  }

}
