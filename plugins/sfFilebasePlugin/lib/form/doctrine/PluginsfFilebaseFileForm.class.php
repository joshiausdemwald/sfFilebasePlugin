<?php

/**
 * PluginsfFilebaseFile form.
 *
 * @package    form
 * @subpackage sfFilebaseFile
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
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


    $this->validatorSchema['sf_filebase_directories_id']->setOption('required', false);
    $this->widgetSchema['tags'] = new sfWidgetFormInput();

    $this->validatorSchema['tags'] = new sfValidatorAnd(array(
      new sfValidatorString(),
      new sfValidatorRegex(array('pattern'=>'#^[^, ;]([^, ;]+[,; ] ?)*?[^, ;]+$#'))
    ), array('required'=>false));

    if($this->isNew())
    {
      $this->widgetSchema['pathname']     = new sfWidgetFormInputFile();
      $this->validatorSchema['pathname']  = new sfFilebasePluginValidatorFile(array('allow_overwrite'=> true, 'required'=>true));
    }
    else
    {
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

  public function processValues($values = null)
  {
    if(empty($values['sf_filebase_directory_id']))
      $values['sf_filebase_directory_id'] = null;

    return parent::processValues($values);
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
    if (is_null($file))
    {
      $file = $this->getValue($field);
    }
    
    $filebase = sfFilebasePlugin::getInstance();
    $dir_id = $this->getValue('sf_filebase_directories_id');
    $filename = sfFilebasePlugin::getInstance()->getFilebaseFile($file->getOriginalName());
    if(!empty($dir_id))
    {
      $dir_object = Doctrine_Query::create()->
        select('*')->
        from('sfFilebaseDirectory d')->
        where('d.id='.$dir_id)->
        execute()->
        get(0);
      $file->setPath($filebase[$dir_object->getPathname()]->getPathname());
      $filename = $filebase->getFilebaseFile($file->getPath() . '/' . $file->getOriginalName());
    }
    parent::saveFile($field, $filename, $file);
    $this->getObject()->setHash($filename->getHash());
    return $filename->getRelativePathFromFilebaseDirectory();
  }
}