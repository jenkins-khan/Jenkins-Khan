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
  public function __construct($view)
  {
    $this->view = $view;
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
