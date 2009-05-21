<?php

/**
 * sfFilebaseDirectory form base class.
 *
 * @package    form
 * @subpackage sf_filebase_directory
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfFilebaseDirectoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'filename' => new sfWidgetFormInput(),
      'hash'     => new sfWidgetFormInput(),
      'comment'  => new sfWidgetFormTextarea(),
      'type'     => new sfWidgetFormInput(),
      'lft'      => new sfWidgetFormInput(),
      'rgt'      => new sfWidgetFormInput(),
      'level'    => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'column' => 'id', 'required' => false)),
      'filename' => new sfValidatorString(array('max_length' => 255)),
      'hash'     => new sfValidatorString(array('max_length' => 255)),
      'comment'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'type'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'lft'      => new sfValidatorInteger(array('required' => false)),
      'rgt'      => new sfValidatorInteger(array('required' => false)),
      'level'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'sfFilebaseDirectory', 'column' => array('filename', 'lft', 'rgt')))
    );

    $this->widgetSchema->setNameFormat('sf_filebase_directory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfFilebaseDirectory';
  }

}
