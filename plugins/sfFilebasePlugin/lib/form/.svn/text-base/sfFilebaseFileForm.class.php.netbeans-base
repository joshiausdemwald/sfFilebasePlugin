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
    $this->validatorSchema['pathname']  = new sfFilebasePluginValidatorFile(array('allow_overwrite'=> true, 'required'=>true));
  }

  /**
   * Saves the current file for the field.
   *
   * @param  string          $field    The field name
   * @param  string          $filename The file name of the file to save
   * @param  sfValidatedFile $file     The validated file to save
   *
   * @return sfPluginValidatorUploadedFile The filename used to save the file
   */
  public function saveFile($field, $filename = null, sfValidatedFile $file = null)
  {
    $file = parent::saveFile($field, $filename, $file);
    //return $file;
    $this->getObject()->setHash($file->getHash());
    return $file->getPathname();

  }
}
