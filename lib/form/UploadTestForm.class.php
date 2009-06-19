<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadTestFormclass
 *
 * @author joshi
 */
class UploadTestForm extends sfForm
{
  public function configure()
  {
    $files = new sfWidgetFormSchema();
    $files[0] = new sfWidgetFormInputFile();
    $files[1] = new sfWidgetFormInputFile();
    $files[2] = new sfWidgetFormInputFile();
    $files[3] = new sfWidgetFormInputFile();

    $vfiles = new sfValidatorSchema();
    $vfiles[0] = new sfFilebasePluginValidatorFile(array('required'=>false));
    $vfiles[1] = new sfFilebasePluginValidatorFile(array('required'=>false));
    $vfiles[2] = new sfFilebasePluginValidatorFile(array('required'=>false));
    $vfiles[3] = new sfFilebasePluginValidatorFile(array('required'=>false));

    $this->setWidgets(array(
      'files' => $files
    ));

    $this->setValidators(array('files' => $vfiles));

    $this->widgetSchema->setNameFormat('uploadtest[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
