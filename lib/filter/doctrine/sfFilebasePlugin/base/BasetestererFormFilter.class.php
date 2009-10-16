<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * testerer filter form base class.
 *
 * @package    filters
 * @subpackage testerer *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasetestererFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'path_name' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'path_name' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('testerer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'testerer';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'path_name' => 'Text',
    );
  }
}