<?php
 
class topMenuComponent extends sfComponent
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
    $jenkinsFactory = new Jenkins_Factory();
    $jenkins = $jenkinsFactory->build($this->getUser()->getJenkinsUrl());
    
    $nbJobDelayed = count(JenkinsRunPeer::getDelayed($this->getUser()));

    $menus = array(
      'Dashboard'             => array(
        'url' => 'jenkins/index'
      ),
      'Create a build branch' => array(
        'url' => 'jenkins/createGroupRun',
      ),
      $nbJobDelayed           => array(
        'url'            => 'jenkins/delayed',
        'title'          => 'See delayed list',
        'class'          => 'btn btn-primary btn-delayed',
        'dropdown_class' => 'btn btn-primary btn-delayed-caret',
        'dropdowns'      => array(
          'Launch all delayed jobs' => array(
            'url'   => 'jenkins/launchAllDelayed',
            'title' => sprintf('Launch all delayed jobs (%s)', $nbJobDelayed),
          ),
        )
      ),
    );

    $activeLink = sprintf(
      '%s/%s',
      $this->getContext()->getActionStack()->getFirstEntry()->getModuleName(),
      $this->getContext()->getActionStack()->getFirstEntry()->getActionName()
    );
    
    $this->setVar('menus', $menus);
    $this->setVar('activeLink', $activeLink);
    $this->setVar('user', $this->getUser());
    $this->setVar('available_jenkins_url', $jenkins->isAvailable() ? $jenkins->getUrl() : null);
  }
}
