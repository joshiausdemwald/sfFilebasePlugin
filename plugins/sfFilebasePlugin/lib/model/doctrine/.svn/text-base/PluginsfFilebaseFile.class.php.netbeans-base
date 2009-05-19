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
abstract class PluginsfFilebaseFile extends BasesfFilebaseFile
{
  public function preDelete($event)
  {
    $f = sfFilebasePlugin::getInstance();
    $f[$this->getPathname()]->delete();
  }

  public function preSave($event)
  {
    parent::preSave($event);
    $values = $this->getModified(true);
    if(!$this->isNew())
    {
      $f = sfFilebasePlugin::getInstance();

      if(array_key_exists('path', $values))
      {
        $source_file_name = array_key_exists('filename', $values) ? $values['filename']  : $this->getFilename();
        $new_pathname = $f->moveFile(
          $values['path'] . '/' . $source_file_name,
          $this->getPath() . '/' . $source_file_name
        );
        $this->setHash($new_pathname->getHash());
      }

      if(array_key_exists('filename', $values))
      {
        $old_pathname = $f[$this->getPath() . '/' . $values['filename']];
        $new_pathname = $old_pathname->rename($this->getFilename());
        $this->setHash($new_pathname->getHash());
      }
    }
  }
}