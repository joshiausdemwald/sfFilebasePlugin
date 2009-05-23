<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * sfFilebaseFile filter form base class.
 *
 * @package    filters
 * @subpackage sfFilebaseFile *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasesfFilebaseFileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'filename'    => new sfWidgetFormFilterInput(),
      'hash'        => new sfWidgetFormFilterInput(),
      'comment'     => new sfWidgetFormFilterInput(),
      'environment' => new sfWidgetFormFilterInput(),
      'application' => new sfWidgetFormFilterInput(),
      'type'        => new sfWidgetFormFilterInput(),
      'root_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'add_empty' => true)),
      'lft'         => new sfWidgetFormFilterInput(),
      'rgt'         => new sfWidgetFormFilterInput(),
      'level'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'filename'    => new sfValidatorPass(array('required' => false)),
      'hash'        => new sfValidatorPass(array('required' => false)),
      'comment'     => new sfValidatorPass(array('required' => false)),
      'environment' => new sfValidatorPass(array('required' => false)),
      'application' => new sfValidatorPass(array('required' => false)),
      'type'        => new sfValidatorPass(array('required' => false)),
      'root_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfFilebaseDirectory', 'column' => 'id')),
      'lft'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('sf_filebase_file_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseFile';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'filename'    => 'Text',
      'hash'        => 'Text',
      'comment'     => 'Text',
      'environment' => 'Text',
      'application' => 'Text',
      'type'        => 'Text',
      'root_id'     => 'ForeignKey',
      'lft'         => 'Number',
      'rgt'         => 'Number',
      'level'       => 'Number',
    );
  }
}