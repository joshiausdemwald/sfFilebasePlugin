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
foreach(sfConfig::get('app_sf_filebase_plugin_filebases', array('uploads' => array('path_name' => sfConfig::get('sf_upload_dir')))) AS $id => $params)
{
  $pathname = isset($params['path_name']) ? $params['path_name'] : null;
  $cache_directory = isset($params['cache_directory']) ? $params['cache_directory'] : null;
  $create = isset($params['create']) ? $params['create'] : false;
  sfFilebasePlugin::createFilebase($id, $pathname, $cache_directory, $create);
}
sfConfig::set('sf_default_filebase', sfFilebasePlugin::getDefault());