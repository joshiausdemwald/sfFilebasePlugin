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
class PluginsfFilebaseDirectoryTable extends sfAbstractFileTable
{
  public static function getChoices()
  {
    $tree_object = Doctrine::getTable('sfFilebaseDirectory')->getTree();
    $tree = $tree_object->fetchTree();
    if(!$tree)
    {
      $root = new sfFilebaseDirectory();
      $root->setFilename(sfFilebasePlugin::getInstance()->getFilename());
      $root->setHash(sfFilebasePlugin::getInstance()->getHash());
      $root->save();
      $tree_object->createRoot($root);
      $tree = $tree_object->fetchTree();
    }
    foreach($tree AS $dir)
    {
      $directory_choices[$dir['id']] = str_repeat('&nbsp;&nbsp;', $dir['level']) . $dir['filename'];
    }
    return $directory_choices;
  }
}