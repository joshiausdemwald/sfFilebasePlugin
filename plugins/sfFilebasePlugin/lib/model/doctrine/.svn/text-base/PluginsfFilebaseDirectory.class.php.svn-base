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
abstract class PluginsfFilebaseDirectory extends BasesfFilebaseDirectory
{
  public function preSave($event)
  {
    parent::preSave($event);
    $values = $this->getModified(true);
    $f = sfFilebasePlugin::getInstance();
    if($this->isNew())
    {
      $p = $this->getParentDirectory();
      $new_dir_path = $p->getPathname() ?
        $p->getPathname() . '/' . $this->getFilename() :
        $this->getFilename();
      
      $new_dir = $f->mkDir($new_dir_path, 0777);
      $this->setPath($new_dir->getPath());
      $this->setHash($new_dir->getHash());
      $this->setLevel($new_dir->getNestingLevel());
    }
    else
    {
      if(array_key_exists('path', $values))
      {
        $source_file_name = isset($values['filename']) ? $values['filename'] : $this->getFilename();

        $source = $f[$values['path'] . '/' . $source_file_name];
        $destination = $f->getFilebaseFile($this->getPath() . '/' . $source_file_name);

        $new_file = $f->moveFile(
          $source,
          $destination
        );
        $this->setHash($new_file->getHash());
        $this->setLevel($new_file->getNestingLevel());
      }
      
      if(isset($values['filename']))
      {
        $new_file = $f->renameFile($this->getPath() . '/' . $values['filename'], $this->getPathname());
        $this->setHash($new_file->getHash());
      }
    }
  }
}