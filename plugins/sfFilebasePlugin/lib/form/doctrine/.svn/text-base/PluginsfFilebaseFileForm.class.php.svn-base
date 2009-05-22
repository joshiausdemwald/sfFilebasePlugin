<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin.adminArea
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */
abstract class PluginsfFilebaseFileForm extends BasesfFilebaseFileForm
{
  public function setup()
  {
    parent::setup();
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['lft']);
    unset($this->widgetSchema['rgt']);
    unset($this->widgetSchema['level']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['lft']);
    unset($this->validatorSchema['rgt']);
    unset($this->validatorSchema['level']);

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorAnd(array(
      new sfValidatorString(),
      new sfValidatorRegex(array('pattern'=>'#^[^, ;]([^, ;]+[,; ] ?)*?[^, ;]+$#'))
    ), array('required'=>false));

    $directory_choices = sfFilebaseDirectoryTable::getChoices();
    $this->widgetSchema['directory']    = new sfWidgetFormChoice(array('choices'=>$directory_choices));
    $this->validatorSchema['directory'] = new sfValidatorChoice(array('choices'=>array_keys($directory_choices)));

    if($this->isNew())
    {
      unset($this->widgetSchema['filename']);
      unset($this->validatorSchema['filename']);
      $this->widgetSchema['hash']     = new sfWidgetFormInputFile();
      $this->validatorSchema['hash']  = new sfFilebasePluginValidatorFile(array('path'=>sfFilebasePlugin::getInstance()->getPathname(),'allow_overwrite'=> true, 'required'=>true));
    }
    else
    {
      unset($this->widgetSchema['hash']);
      unset($this->validatorSchema['hash']);
      $this->validatorSchema['filename'] = new sfValidatorAnd(
      array (
          new sfValidatorString(),
          new sfValidatorRegex(array('pattern'=>'#^[^\s\\\/]+$#i'))
        )
      );
      $tag_string = $this->getObject()->getTagsAsString();
      $this->widgetSchema['tags']->setDefault($tag_string);
      $this->widgetSchema['directory']->setDefault($this->getObject()->getNode()->getParent()->getId());
    }

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array(
        'callback' => array(
          $this,
          'checkUpload'
        )
      ))
    );
  }

  public function checkUpload(sfValidatorCallback $validator, $values, $parameters = null)
  {
    $filename = '';
    if($this->isNew())
    {
      $filename = $values['hash']->getOriginalName();
    }
    else
    {
      $filename = $values['filename'];
    }
    $validator->addMessage('unique', 'A file with that name already exists in the destinated directory.');
    $parent = Doctrine::getTable('sfFilebaseDirectory')->find($values['directory']);
    if($parent->getNode()->hasChildren())
    {
      foreach($parent->getNode()->getChildren() AS $file)
      {
        if($file != $this->getObject() && $file->getFilename() == $filename)
        {
          throw new sfValidatorError($validator, 'unique');
        }
      }
    }
    return $values;
  }

  /**
   * Betray him in a very nasty way ...
   * This is not a real column, but who cares...
   *
   * @param array $values
   */
  public function updateTagsColumn($tags)
  {
    $this->getObject()->setTags(sfFilebaseTagTable::splitTags($tags));
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
    $file = $this->getValue($field);
    $this->getObject()->setFilename($file->getOriginalName());
    return parent::saveFile($field, $filename, $file)->getFilename();
  }

  protected function doSave($con = null)
  {
    $con === null && $this->getConnection();

    $this->updateObject();

    $destination_node = null;
    if($this->getValue('directory'))
    {
      $destination_node = Doctrine::getTable('sfFilebaseDirectory')->findById($this->getValue('directory'))->get(0);
    }
    else
    {
      $destination_node = Doctrine::getTable('sfFilebaseDirectory')->getTree()->fetchRoot();
    }
    if($this->isNew())
    {
      $this->getObject()->getNode()->insertAsLastChildOf($destination_node);
    }
    else
    {
      $this->getObject()->getNode()->moveAsLastChildOf($destination_node);
    }

    // embedded forms
    $this->saveEmbeddedForms($con);
  }
}