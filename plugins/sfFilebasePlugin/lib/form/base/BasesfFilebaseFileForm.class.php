<?php

/**
 * sfFilebaseFile form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseFileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'pathname'                   => new sfWidgetFormInput(),
      'hash'                       => new sfWidgetFormInput(),
      'sf_filebase_directories_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseDirectory', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorPropelChoice(array('model' => 'sfFilebaseFile', 'column' => 'id', 'required' => false)),
      'pathname'                   => new sfValidatorString(array('max_length' => 255)),
      'hash'                       => new sfValidatorString(array('max_length' => 255)),
      'sf_filebase_directories_id' => new sfValidatorPropelChoice(array('model' => 'sfFilebaseDirectory', 'column' => 'id', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'sfFilebaseFile', 'column' => array('pathname'))),
        new sfValidatorPropelUnique(array('model' => 'sfFilebaseFile', 'column' => array('hash'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_filebase_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseFile';
  }


}
