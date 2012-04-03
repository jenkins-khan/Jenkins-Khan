<?php

class ConfigureUserProfileForm extends ProfileForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'jenkins_url'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => true)),
      'jenkins_url'      => new sfValidatorString(array('max_length' => 256, 'required' => true)),
    ));

    $this->widgetSchema->setNameFormat('profile[%s]');
  }
}
