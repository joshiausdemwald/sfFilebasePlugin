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

/**
 * sf_filebase_directory module helper.
 *
 * @package    test
 * @subpackage sf_filebase_directory
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sf_filebase_directoryGeneratorHelper extends BaseSf_filebase_directoryGeneratorHelper
{
  public function linkToList($params)
  {
    $parent = sfContext::getInstance()->getUser()->getAttribute('source_node', null, 'sf_filebase_plugin');
    $arguments = $parent === null ? '' : '?id='.$parent->getId();
    return '<li class="sf_admin_action_list">'.link_to(__($params['label'], array(), 'sf_admin'), '@sf_filebase_gallery' . $arguments).'</li>';
  }
}
