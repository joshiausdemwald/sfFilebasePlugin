<?php

/**
 * sfFilebaseTag form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseTagForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'sf_filebase_file_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseFile', 'add_empty' => true)),
      'tag'                 => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorPropelChoice(array('model' => 'sfFilebaseTag', 'column' => 'id', 'required' => false)),
      'sf_filebase_file_id' => new sfValidatorPropelChoice(array('model' => 'sfFilebaseFile', 'column' => 'id', 'required' => false)),
      'tag'                 => new sfValidatorString(array('max_length' => 255)),
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
