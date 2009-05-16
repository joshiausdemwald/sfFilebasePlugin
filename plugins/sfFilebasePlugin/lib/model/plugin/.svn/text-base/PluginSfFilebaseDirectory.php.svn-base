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
class PluginSfFilebaseDirectory extends BasesfFilebaseDirectory
{
  public function __toString()
  {
    try
    {
      $filebase = sfFilebasePlugin::getInstance();
      $pathname = $this->getsfFilebaseFile()->getPathname();
      $file = $filebase[$pathname];
      return $file->getName();
    }
    catch (Exception $e)
    {
      return (string)$e;
    }
  }
}
