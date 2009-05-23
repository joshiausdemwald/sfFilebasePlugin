<?php

/**
 * sfAbstractFile form base class.
 *
 * @package    form
 * @subpackage sf_abstract_file
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasesfAbstractFileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'filename'    => new sfWidgetFormInput(),
      'hash'        => new sfWidgetFormInput(),
      'comment'     => new sfWidgetFormTextarea(),
      'environment' => new sfWidgetFormInput(),
      'application' => new sfWidgetFormInput(),
      'type'        => new sfWidgetFormInput(),
      'root_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'add_empty' => true)),
      'lft'         => new sfWidgetFormInput(),
      'rgt'         => new sfWidgetFormInput(),
      'level'       => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'sfAbstractFile', 'column' => 'id', 'required' => false)),
      'filename'    => new sfValidatorString(array('max_length' => 255)),
      'hash'        => new sfValidatorString(array('max_length' => 255)),
      'comment'     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'environment' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'application' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'root_id'     => new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'required' => false)),
      'lft'         => new sfValidatorInteger(array('required' => false)),
      'rgt'         => new sfValidatorInteger(array('required' => false)),
      'level'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_abstract_file[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfAbstractFile';
  }

}
