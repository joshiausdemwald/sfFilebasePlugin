<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * sfFilebaseDirectory filter form base class.
 *
 * @package    filters
 * @subpackage sfFilebaseDirectory *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasesfFilebaseDirectoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'filename' => new sfWidgetFormFilterInput(),
      'hash'     => new sfWidgetFormFilterInput(),
      'comment'  => new sfWidgetFormFilterInput(),
      'type'     => new sfWidgetFormFilterInput(),
      'lft'      => new sfWidgetFormFilterInput(),
      'rgt'      => new sfWidgetFormFilterInput(),
      'level'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'filename' => new sfValidatorPass(array('required' => false)),
      'hash'     => new sfValidatorPass(array('required' => false)),
      'comment'  => new sfValidatorPass(array('required' => false)),
      'type'     => new sfValidatorPass(array('required' => false)),
      'lft'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('sf_filebase_directory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseDirectory';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'filename' => 'Text',
      'hash'     => 'Text',
      'comment'  => 'Text',
      'type'     => 'Text',
      'lft'      => 'Number',
      'rgt'      => 'Number',
      'level'    => 'Number',
    );
  }
}