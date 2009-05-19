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
abstract class PluginsfFilebaseFileForm extends BasesfFilebaseFileForm
{
  public function setup()
  {
    parent::setup();
    unset($this->widgetSchema['hash']);
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['level']);
    unset($this->validatorSchema['hash']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['level']);
    unset($this->widgetSchema['path']);
    unset($this->validatorSchema['path']);

    $this->validatorSchema['sf_filebase_directories_id']->setOption('required', false);
    $this->widgetSchema['tags'] = new sfWidgetFormInput();

    $this->validatorSchema['tags'] = new sfValidatorAnd(array(
      new sfValidatorString(),
      new sfValidatorRegex(array('pattern'=>'#^[^, ;]([^, ;]+[,; ] ?)*?[^, ;]+$#'))
    ), array('required'=>false));

    if($this->isNew())
    {
      $this->widgetSchema['filename']     = new sfWidgetFormInputFile();
      $this->validatorSchema['filename']  = new sfFilebasePluginValidatorFile(array('allow_overwrite'=> true, 'required'=>true));
    }
    else
    {
      $this->validatorSchema['filename'] = new sfValidatorAnd(
      array (
          new sfValidatorString(),
          new sfValidatorRegex(array('pattern'=>'#^[^\s\\\/]+$#i'))
        )
      );
      $tag_string = $this->getObject()->getTagsAsString();
      $this->widgetSchema['tags']->setDefault($tag_string);
    }
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
    if (!$this->validatorSchema[$field] instanceof sfValidatorFile)
    {
      throw new LogicException(sprintf('You cannot save the current file for field "%s" as the field is not a file.', $field));
    }
    $uploaded_file = $file;
    if (is_null($file))
    {
      $uploaded_file = $this->getValue($field);
    }

    $filebase = sfFilebasePlugin::getInstance();
    $dir_id = $this->getValue('sf_filebase_directories_id');
    $original_filename = sfFilebasePlugin::getInstance()->getFilebaseFile($uploaded_file->getOriginalName());
    $save_path_name = $original_filename;
    if(!empty($dir_id))
    {
      $dir_object = Doctrine_Query::create()->
        select('*')->
        from('sfFilebaseDirectory d')->
        where('d.id='.$dir_id)->
        execute()->
        get(0);
      $this->getObject()->setParentDirectory($dir_object);
      $uploaded_file->setPath($filebase[$dir_object->getPathname()]->getPathname());
      $save_path_name = $filebase->getFilebaseFile($uploaded_file->getPath() . '/' . $uploaded_file->getOriginalName());
    }
    $saved_file = parent::saveFile($field, $save_path_name, $file);
    $this->getObject()->setHash($saved_file->getHash());
    $this->getObject()->setPath($saved_file->getPath());
    return $saved_file->getFilename();
  }
}