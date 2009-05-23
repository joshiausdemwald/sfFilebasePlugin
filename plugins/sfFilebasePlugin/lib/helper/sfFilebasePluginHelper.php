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

/**
 * helper for easily retrieve an instance of sfFilebasePlugin
 * in your view file.
 *
 * Enable the helper by typing
 *   <?php use_helper('FilebaseHelper');?>
 *
 * @param string $path_name
 * @param string $cache_directory
 * @return sfFilebasePlugin $filebase
 */
function get_filebase($path_name = null, $cache_directory = null)
{
  return sfFilebasePlugin::getInstance($path_name, $cache_directory);
}

function url_for_asset($path_name)
{
  $f = sfFilebasePlugin::getInstance();
}