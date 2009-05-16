<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * sfFilebaseDirectory filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseDirectoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_filebase_files_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseFile', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'sf_filebase_files_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfFilebaseFile', 'column' => 'id')),
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
      'sf_filebase_files_id' => 'ForeignKey',
      'id'                   => 'Number',
    );
  }
}
