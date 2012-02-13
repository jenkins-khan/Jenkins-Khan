<?php

/**
 * JenkinsRun filter form base class.
 *
 * @package    JenkinsKhan
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseJenkinsRunFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'jenkins_group_run_id' => new sfWidgetFormPropelChoice(array('model' => 'JenkinsGroupRun', 'add_empty' => true)),
      'job_name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'job_build_number'     => new sfWidgetFormFilterInput(),
      'git_branch'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'launched'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'parameters'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'jenkins_group_run_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'JenkinsGroupRun', 'column' => 'id')),
      'job_name'             => new sfValidatorPass(array('required' => false)),
      'job_build_number'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'git_branch'           => new sfValidatorPass(array('required' => false)),
      'launched'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parameters'           => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jenkins_run_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JenkinsRun';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'jenkins_group_run_id' => 'ForeignKey',
      'job_name'             => 'Text',
      'job_build_number'     => 'Number',
      'git_branch'           => 'Text',
      'launched'             => 'Number',
      'parameters'           => 'Text',
    );
  }
}
