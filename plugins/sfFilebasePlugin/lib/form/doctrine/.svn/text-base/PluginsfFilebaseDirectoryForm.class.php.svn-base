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
    unset($this->widgetSchema['path']);
    unset($this->widgetSchema['hash']);
    unset($this->widgetSchema['type']);
    unset($this->widgetSchema['level']);
    unset($this->validatorSchema['path']);
    unset($this->validatorSchema['type']);
    unset($this->validatorSchema['hash']);
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

    if(!$this->isNew())
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
}