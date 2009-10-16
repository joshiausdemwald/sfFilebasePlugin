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
$path_name = sfConfig::get('app_sf_filebase_plugin_path_name', sfConfig::get('sf_upload_dir'));
sfConfig::set('sf_public_filebase', $f = new sfFilebasePlugin($path_name));
sfFilebasePlugin::setDefaultFilebase($f);