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
abstract class PluginsfFilebaseDirectoryForm extends BasesfFilebaseDirectoryForm
{
  public function setup()
  {
    parent::setup();
    unset($this->widgetSchema['hash']);
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['lft']);
    unset($this->widgetSchema['rgt']);
    unset($this->widgetSchema['level']);

    unset($this->validatorSchema['hash']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['lft']);
    unset($this->validatorSchema['rgt']);
    unset($this->validatorSchema['level']);
    
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

    $directory_choices = sfFilebaseDirectoryTable::getChoices();
    $this->widgetSchema['directory']    = new sfWidgetFormChoice(array('choices'=>$directory_choices));
    $this->validatorSchema['directory'] = new sfValidatorChoice(array('choices'=>array_keys($directory_choices)));

    if(!$this->isNew())
    {
      $tag_string = $this->getObject()->getTagsAsString();
      $this->widgetSchema['tags']->setDefault($tag_string);

      $p = $this->getObject()->getNode()->getParent();
      if($p)
      {
        $this->widgetSchema['directory']->setDefault($this->getObject()->getNode()->getParent()->getId());
      }
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
    $filename = $values['filename'];
    $validator->addMessage('unique', 'A file with that name already exists in the destinated directory.');
    $validator->addMessage('invalid', 'You are not allowed to move a directory into itself or one of its children.');
    $parent = Doctrine::getTable('sfFilebaseDirectory')->find($values['directory']);
    if($parent->getNode()->hasChildren())
    {
      foreach($parent->getNode()->getChildren() AS $file)
      {
        if($file !== $this->getObject() && $file->getFilename() == $filename)
        {
          throw new sfValidatorError($validator, 'unique');
        }
      }
    }
    while(true)
    {
      if($parent === $this->getObject())
      {
        throw new sfValidatorError($validator, 'invalid');
      }
      $parent = $parent->getNode()->getParent();
      if(!$parent)
      {
        break;
      }
    }
    return $values;
  }

  public function processValues($values = null)
  {
    if($this->isNew())
    {
      $values['hash'] = $this->getObject()->generatehashFilename();
    }
    return parent::processValues($values);
  }

  protected function doSave($con = null)
  {
    $con === null && $con = $this->getConnection();

    parent::updateObject();

    $destination_node = null;
    if($this->getValue('directory'))
    {
      $destination_node = Doctrine::getTable('sfFilebaseDirectory')->find($this->getValue('directory'));
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