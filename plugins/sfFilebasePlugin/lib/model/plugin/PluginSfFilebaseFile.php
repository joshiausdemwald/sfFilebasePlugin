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
class PluginSfFilebaseFile extends BasesfFilebaseFile
{
  public function __toString()
  {
    try
    {
      $filebase = sfFilebasePlugin::getInstance();
      $pathname = $this->getPathname();
      $file = $filebase[$pathname];
      return $file->getName();
    }
    catch (Exception $e)
    {
      return (string)$e;
    }
  }

  /**
   * Sets the tags for this file.
   *
   * @param array $tags
   */
  public function setTags(array $tags)
  {
    $c = new Criteria();
    $c->add(sfFilebaseTagPeer::SF_FILEBASE_FILE_ID, $this->getId());
    sfFilebaseTagPeer::doDelete($c);

    foreach ($tags AS $tag)
    {
      $new_tag = new sfFilebaseTag();
      $new_tag->setTag($tag);
      $new_tag->setsfFilebaseFile($this);
    }
  }

  /**
   * Returns the tags as a string representation.
   * @param string $separator
   * @return string $tag_string
   */
  public function getTagsAsString($separator = ', ')
  {
    $tags = array();
    foreach($this->getsfFilebaseTags() AS $tag)
    {
      $tags[] = $tag->getTag();
    }
    return implode($separator, $tags);
  }

  /**
   * Adds a foreign key constraint to
   * a tag.
   * @param sfFilebaseTag $tag
   * @return sfFilebaseFile $this;
   */
  public function addTag(sfFilebaseTag $tag)
  {
    $fk = new sfFilebaseFkFileTag();
    $fk->setsfFilebaseFile($this);
    $fk->setsfFilebaseTag($tag);
    $fk->save();
  }
}
