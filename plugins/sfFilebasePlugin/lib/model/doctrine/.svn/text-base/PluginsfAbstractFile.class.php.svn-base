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
abstract class PluginsfAbstractFile extends BasesfAbstractFile
{
  /**
   * Returns the tags as a string representation.
   * @param string $separator
   * @return string $tag_string
   */
  public function getTagsAsString($separator = ', ')
  {
    $tags = array();
    foreach($this->getTags() AS $tag)
    {
      $tags[] = $tag->getTag();
    }
    return implode($separator, $tags);
  }

  /**
   * Sets the tags for this file.
   *
   * @param array $tags
   */
  public function setTags(array $tags)
  {
    Doctrine_Query::create()->
      from('sfFilebaseTag t')->
      delete()->
      where('t.sf_abstract_files_id='.$this->getId())->
      execute();

    foreach ($tags AS $tag)
    {
      $new_tag = new sfFilebaseTag();
      $new_tag->setTag($tag);
      $new_tag->setFile($this);
      $this->tags[] = $new_tag;
    }
  }

  public function __toString()
  {
    return (string)$this->getFilename();
  }

  /**
   * This method is only for the displaying in sfDoctrine Form.
   * It renders an indent for each directory/file.
   *
   * @return <type>
   */
  public function getFilenameIndent()
  {
    return str_repeat('&nbsp;', $this->getLevel()) . $this->getFilename();
  }

  public function generatehashFilename($file = null)
  {
    if($file === null)
    {
      return md5(uniqid(rand(), true));
    }
    return md5(uniqid(rand(), true)) . $file->getExtension();
  }
}