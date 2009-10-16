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
abstract class PluginsfFilebaseDirectory extends BasesfFilebaseDirectory
{
  public function preDelete($event)
  {
    if($this->getNode()->isRoot())
      throw new sfFilebasePluginException(sprintf('Root directory %s cannot be deleted.', $this->getFilename()));

    if($this->getNode()->hasChildren())
    {
      foreach($this->getNode()->getChildren() AS $entry)
      {
        $entry->delete();
      }
    }
  }

  public function postDelete($event)
  {
    sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent(
        $this, 'sfFilebasePlugin.filebase.directory_deleted'
    ));
  }

  public function postInsert($event)
  {
    sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent(
        $this, 'sfFilebasePlugin.filebase.directory_inserted'
    ));
  }

  public function postUpdate($event)
  {
    sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent(
        $this, 'sfFilebasePlugin.filebase.directory_updated'
    ));
  }
}