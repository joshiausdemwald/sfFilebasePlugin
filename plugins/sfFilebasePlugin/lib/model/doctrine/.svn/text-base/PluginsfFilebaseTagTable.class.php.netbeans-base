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
class PluginsfFilebaseTagTable extends Doctrine_Table
{
  /**
   * Splits a tag-string like
   * tag1, tag2 tag3; tag4 ... tag-n into
   * an array.
   *
   * @param string $tags
   * @return array $tags
   */
  public static function splitTags($tags)
  {
    return preg_split('#[,; ] ?#', $tags, null, PREG_SPLIT_NO_EMPTY);
  }
}