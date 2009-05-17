<?php

/**
 * sfFilebaseTag form base class.
 *
 * @package    form
 * @subpackage sf_filebase_tag
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfFilebaseTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'sf_abstract_files_id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfAbstractFile', 'add_empty' => true)),
      'tag'                  => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseTag', 'column' => 'id', 'required' => false)),
      'sf_abstract_files_id' => new sfValidatorDoctrineChoice(array('model' => 'sfAbstractFile', 'required' => false)),
      'tag'                  => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('sf_filebase_tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseTag';
  }

}
