<?php

/**
 * ProfileConfiguration filter form base class.
 *
 * @package    JenkinsKhan
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseProfileConfigurationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_id' => new sfWidgetFormPropelChoice(array('model' => 'Profile', 'add_empty' => true)),
      'key'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'profile_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Profile', 'column' => 'id')),
      'key'        => new sfValidatorPass(array('required' => false)),
      'value'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_configuration_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileConfiguration';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'profile_id' => 'ForeignKey',
      'key'        => 'Text',
      'value'      => 'Text',
    );
  }
}
