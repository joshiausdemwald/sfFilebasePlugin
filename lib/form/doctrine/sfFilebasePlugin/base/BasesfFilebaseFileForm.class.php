<?php

/**
 * sfFilebaseFile form base class.
 *
 * @package    form
 * @subpackage sf_filebase_file
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfFilebaseFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'pathname'                 => new sfWidgetFormInput(),
      'hash'                     => new sfWidgetFormInput(),
      'comment'                  => new sfWidgetFormTextarea(),
      'sf_filebase_directory_id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'add_empty' => true)),
      'type'                     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseFile', 'column' => 'id', 'required' => false)),
      'pathname'                 => new sfValidatorString(array('max_length' => 255)),
      'hash'                     => new sfValidatorString(array('max_length' => 255)),
      'comment'                  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'sf_filebase_directory_id' => new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'required' => false)),
      'type'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfFilebaseFile', 'column' => array('pathname'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfFilebaseFile', 'column' => array('hash'))),
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
