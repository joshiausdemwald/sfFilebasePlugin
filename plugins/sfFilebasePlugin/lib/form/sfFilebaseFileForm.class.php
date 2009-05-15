<?php

/**
 * sfFilebaseFile form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class sfFilebaseFileForm extends BasesfFilebaseFileForm
{
  public function configure()
  {
    $this->widgetSchema['pathname']     = new sfWidgetFormInputFile();
    $this->validatorSchema['pathname']  = new sfFilebasePluginValidatorFile(array('required'=>true));
  }
}
