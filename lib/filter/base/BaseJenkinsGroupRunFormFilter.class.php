<?php

/**
 * JenkinsGroupRun filter form base class.
 *
 * @package    JenkinsKhan
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseJenkinsGroupRunFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'date'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'user_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'label'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'git_branch' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'date'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'user_id'    => new sfValidatorPass(array('required' => false)),
      'label'      => new sfValidatorPass(array('required' => false)),
      'git_branch' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('jenkins_group_run_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'JenkinsGroupRun';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'date'       => 'Date',
      'user_id'    => 'Text',
      'label'      => 'Text',
      'git_branch' => 'Text',
    );
  }
}
