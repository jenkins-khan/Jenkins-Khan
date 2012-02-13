<?php

/**
 * Profile filter form base class.
 *
 * @package    JenkinsKhan
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseProfileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_guard_user_id' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'jenkins_url'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sf_guard_user_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'jenkins_url'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'sf_guard_user_id' => 'ForeignKey',
      'jenkins_url'      => 'Text',
    );
  }
}
