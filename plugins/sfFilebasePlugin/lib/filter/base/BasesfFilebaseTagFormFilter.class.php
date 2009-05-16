<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * sfFilebaseTag filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseTagFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_filebase_file_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseFile', 'add_empty' => true)),
      'tag'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sf_filebase_file_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfFilebaseFile', 'column' => 'id')),
      'tag'                 => new sfValidatorPass(array('required' => false)),
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
      'id'                  => 'Number',
      'sf_filebase_file_id' => 'ForeignKey',
      'tag'                 => 'Text',
    );
  }
}
