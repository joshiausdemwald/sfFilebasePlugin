<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * sfFilebaseTag filter form base class.
 *
 * @package    filters
 * @subpackage sfFilebaseTag *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasesfFilebaseTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_abstract_files_id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfAbstractFile', 'add_empty' => true)),
      'tag'                  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sf_abstract_files_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfAbstractFile', 'column' => 'id')),
      'tag'                  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_filebase_tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseTag';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'sf_abstract_files_id' => 'ForeignKey',
      'tag'                  => 'Text',
    );
  }
}