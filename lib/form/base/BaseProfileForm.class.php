<?php

/**
 * Profile form base class.
 *
 * @method Profile getObject() Returns the current form's model object
 *
 * @package    JenkinsKhan
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseProfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'sf_guard_user_id' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'jenkins_url'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'sf_guard_user_id' => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'jenkins_url'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }


}
