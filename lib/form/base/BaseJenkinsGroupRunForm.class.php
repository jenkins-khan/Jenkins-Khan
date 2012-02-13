<?php

/**
 * JenkinsGroupRun form base class.
 *
 * @method JenkinsGroupRun getObject() Returns the current form's model object
 *
 * @package    JenkinsKhan
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseJenkinsGroupRunForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'date'       => new sfWidgetFormDate(),
      'user_id'    => new sfWidgetFormInputText(),
      'label'      => new sfWidgetFormInputText(),
      'git_branch' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'date'       => new sfValidatorDate(),
      'user_id'    => new sfValidatorString(array('max_length' => 36)),
      'label'      => new sfValidatorString(array('max_length' => 100)),
      'git_branch' => new sfValidatorString(array('max_length' => 32)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'JenkinsGroupRun', 'column' => array('user_id', 'git_branch')))
    );

    $this->widgetSchema->setNameFormat('jenkins_group_run[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JenkinsGroupRun';
  }


}
