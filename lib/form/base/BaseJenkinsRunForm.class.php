<?php

/**
 * JenkinsRun form base class.
 *
 * @method JenkinsRun getObject() Returns the current form's model object
 *
 * @package    JenkinsKhan
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseJenkinsRunForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'jenkins_group_run_id' => new sfWidgetFormPropelChoice(array('model' => 'JenkinsGroupRun', 'add_empty' => false)),
      'job_name'             => new sfWidgetFormInputText(),
      'job_build_number'     => new sfWidgetFormInputText(),
      'git_branch'           => new sfWidgetFormInputText(),
      'launched'             => new sfWidgetFormInputText(),
      'parameters'           => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'jenkins_group_run_id' => new sfValidatorPropelChoice(array('model' => 'JenkinsGroupRun', 'column' => 'id')),
      'job_name'             => new sfValidatorString(array('max_length' => 30)),
      'job_build_number'     => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'git_branch'           => new sfValidatorString(array('max_length' => 40)),
      'launched'             => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'parameters'           => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jenkins_run[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JenkinsRun';
  }


}
