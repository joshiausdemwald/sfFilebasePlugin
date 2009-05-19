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
abstract class PluginsfFilebaseDirectoryForm extends BasesfFilebaseDirectoryForm
{
  public function setup()
  {
    parent::setup();
    unset($this->widgetSchema['path']);
    unset($this->widgetSchema['hash']);
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['level']);
    unset($this->validatorSchema['path']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['hash']);
    unset($this->validatorSchema['level']);

    $this->widgetSchema['sf_filebase_directories_id'] = new sfWidgetFormDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'method'=>'getFilenameIndent', 'add_empty' => true));

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorAnd(array(
      new sfValidatorString(),
      new sfValidatorRegex(array('pattern'=>'#^[^, ;]([^, ;]+[,; ] ?)*?[^, ;]+$#'))
    ), array('required'=>false));

    $this->validatorSchema['filename'] = new sfValidatorAnd(
      array(
        new sfValidatorString(),
        new sfValidatorRegex(array('pattern'=>'#^[\w\-\.]+$#'))
      ),
      array('required'=>true)
    );

    if(!$this->isNew())
    {
      $tag_string = $this->getObject()->getTagsAsString();
      $this->widgetSchema['tags']->setDefault($tag_string);
      
      $this->validatorSchema['sf_filebase_directories_id'] = new sfValidatorAnd
      (
        array
        (
          new sfValidatorDoctrineChoice(array('model' => 'sfFilebaseDirectory', 'required' => false)),
          new sfValidatorCallback(array('callback'=>array(
            $this,
            'validateDirectoryPath'
          ), 'arguments'=>array(
                'dir'=>$this->getObject()
              )))
        ),
        array(
          'required'=>false
        )
      );
    }
  }

  public function validateDirectoryPath($validator, $value, $arguments)
  {
    $f = sfFilebasePlugin::getInstance();
    $dest_p_dir_object        = Doctrine::getTable('sfFilebaseDirectory')->find($value);
    $source_dir_object        = $arguments['dir'];
    
    $source_dir = $source_dir_object->getPathname() === null ? $f[$f->getPathname() . '/' . $source_dir_object->getFilename()]  : $f[$source_dir_object->getPathname()];
    $dest_dir   = $dest_p_dir_object->getPathname() === null ? $f->getFilebaseFile($f . '/' . $source_dir_object->getFilename()) : $f->getFilebaseFile($dest_p_dir_object->getPathname() . '/' . $source_dir_object->getFilename());

    if(!$dest_dir->liesWithin($source_dir))
    {
      return $value;
    }
    throw new sfValidatorError($validator, 'invalid');
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
}