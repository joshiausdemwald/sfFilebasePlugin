<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * sfFilebaseFile filter form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseFileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'pathname'                   => new sfWidgetFormFilterInput(),
      'hash'                       => new sfWidgetFormFilterInput(),
      'comment'                    => new sfWidgetFormFilterInput(),
      'sf_filebase_directories_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseDirectory', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'pathname'                   => new sfValidatorPass(array('required' => false)),
      'hash'                       => new sfValidatorPass(array('required' => false)),
      'comment'                    => new sfValidatorPass(array('required' => false)),
      'sf_filebase_directories_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfFilebaseDirectory', 'column' => 'id')),
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
      'id'                         => 'Number',
      'pathname'                   => 'Text',
      'hash'                       => 'Text',
      'comment'                    => 'Text',
      'sf_filebase_directories_id' => 'ForeignKey',
    );
  }
}
