<?php

/**
 * PluginsfFilebaseDirectory form.
 *
 * @package    form
 * @subpackage sfFilebaseDirectory
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginsfFilebaseDirectoryForm extends BasesfFilebaseDirectoryForm
{
  public function setup()
  {
    parent::setup();
    unset($this->widgetSchema['hash']);
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['level']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['hash']);
    unset($this->validatorSchema['level']);

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorAnd(array(
      new sfValidatorString(),
      new sfValidatorRegex(array('pattern'=>'#^[^, ;]([^, ;]+[,; ] ?)*?[^, ;]+$#'))
    ), array('required'=>false));

    $this->validatorSchema['pathname'] = new sfValidatorAnd(
      array(
        new sfValidatorString(),
        new sfValidatorRegex(array('pattern'=>'#^[\w\-\.]+$#'))
      ),
      array('required'=>true)
    );

    $dirs = Doctrine_Query::create()->
                            select('*')->
                            from('sfFilebaseDirectory d')->
                            execute();

    if(!$this->isNew())
    {
      unset($this->widgetSchema['sf_filebase_directories_id']);
      unset($this->validatorSchema['sf_filebase_directories_id']);
      unset($this->widgetSchema['pathname']);
      unset($this->validatorSchema['pathname']);
      
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
    if($this->isNew())
    {
      $filebase = sfFilebasePlugin::getInstance();
      $name   = $values['pathname'];
      $dir_id = $values['sf_filebase_directories_id'];
      $path = null;
      if($dir_id===null)
      {
        $path = $filebase;
      }
      else
      {
        $dir_object = Doctrine_Query::create()->select('*')->
                      from('sfFilebaseDirectory d')->
                      where('d.id='.$dir_id)->execute()->get(0);
        $path = $filebase->getFilebaseFile($dir_object->getPathname());
      }
      $pathname = $filebase->mkDir($path . '/' . $name, 0777);
      $values['pathname']  = $pathname->getRelativePathFromFilebaseDirectory();
      $this->getObject()->setLevel($pathname->getNestingLevel());
      $this->getObject()->setHash($pathname->getHash());
    }
    return parent::processValues($values);
  }
}