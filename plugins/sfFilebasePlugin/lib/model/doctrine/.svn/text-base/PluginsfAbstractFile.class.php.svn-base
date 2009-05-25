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
class PluginsfAbstractFile extends BasesfAbstractFile
{
  public function cleanupTags($tags)
  {
    $tags_array = preg_split('#[,; ] ?#', $tags, null, PREG_SPLIT_NO_EMPTY);
    return implode(' ', $tags_array);
  }

  public function getTagsFormatted($separator=', ')
  {
    return str_replace(' ', $separator, $this->getTags());
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

  public function generateHashFilename($file = null)
  {
    if($file === null)
    {
      return md5(uniqid(rand(), true));
    }
    return md5(uniqid(rand(), true)) . strtolower($file->getExtension());
  }

  public function hasTag($tag)
  {
    return strpos($this->getTags(), $tag) !== false;
  }
}