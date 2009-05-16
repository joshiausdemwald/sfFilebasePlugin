<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
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
