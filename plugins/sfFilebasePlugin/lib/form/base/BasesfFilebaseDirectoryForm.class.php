<?php

/**
 * sfFilebaseDirectory form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 16976 2009-04-04 12:47:44Z fabien $
 */
class BasesfFilebaseDirectoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_filebase_files_id' => new sfWidgetFormPropelChoice(array('model' => 'sfFilebaseFile', 'add_empty' => true)),
      'id'                   => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'sf_filebase_files_id' => new sfValidatorPropelChoice(array('model' => 'sfFilebaseFile', 'column' => 'id', 'required' => false)),
      'id'                   => new sfValidatorPropelChoice(array('model' => 'sfFilebaseDirectory', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_filebase_directory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseDirectory';
  }


}
