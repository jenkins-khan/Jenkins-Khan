<?php

/** @method myUser getUser() */
abstract class baseApiJenkinsAction extends sfAction
{

  /**
   * @var Jenkins
   */
  protected $jenkins;
  
  /**
   * @var modelFactory
   */
  protected $modelFactory;


  /**
   * @param sfWebRequest $request
   *
   * @return void
   */
  abstract protected function getContent($request);

  /**
   *
   */
  public function preExecute()
  {
    $this->forward404Unless(Configuration::get('api_enabled', false), 'Api is disabled');
    $jenkinsFactory = new Jenkins_Factory();
    $jenkins        = $jenkinsFactory->build($this->getJenkinsUrl());
    $this->setJenkins($jenkins);
    $this->setModelFactory(new modelFactory());
  }

  /**
   * @param sfRequest $request
   *
   * @return string
   */
  public function execute($request)
  {
    $return = array(
      'status'  => 0,
      'message' => 'OK',
      'content' => array(),
    );

    try
    {
      $return['content'] = $this->getContent($request);
    }
    catch (Exception $e)
    {
      $return['status']  = $e->getCode() !== 0 ? $e->getCode() : 1;
      $return['message'] = $e->getMessage();
    }

    return $this->renderText(json_encode($return));
  }


  /**
   * @return string
   */
  protected function getJenkinsUrl()
  {
    return $this->getUser()->getProfile()->getJenkinsUrl();
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
   * @return $this
   */
  public function setJenkins(Jenkins $jenkins)
  {
    $this->jenkins = $jenkins;

    return $this;
  }

  /**
   *
   * @return \modelFactory
   */
  public function getModelFactory()
  {
    return $this->modelFactory;
  }

  /**
   * @param \modelFactory $modelFactory
   * 
   * @return \baseApiJenkinsAction
   */
  public function setModelFactory(modelFactory $modelFactory)
  {
    $this->modelFactory = $modelFactory;

    return $this;
  }

}
