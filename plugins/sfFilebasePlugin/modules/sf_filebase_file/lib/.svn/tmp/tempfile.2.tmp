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
 * sf_filebase_file module helper.
 *
 * @package    test
 * @subpackage sf_filebase_file
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sf_filebase_fileGeneratorHelper extends BaseSf_filebase_fileGeneratorHelper
{

  public function linkToSave($object, $params)
  {
    if($object->isNew())
    {
      return '<li class="sf_admin_action_save"><input type="submit" value="'.__($params['label'], array(), 'sf_admin').'" /></li>';
    }
    else
    {
      return '<li class="sf_admin_action_save"><input type="submit" value="'.__($params['label'], array(), 'sf_admin').'" /></li>';
    }
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }

    return '<noscript><li class="sf_admin_action_save_and_add"><input type="submit" value="'.__($params['label'], array(), 'sf_admin').'" name="_save_and_add" /></li></noscript>';
  }
  
  public function linkToList($params)
  {
    $parent = sfContext::getInstance()->getUser()->getAttribute('source_node', null, 'sf_filebase_plugin');
    $arguments = $parent === null ? '' : '?id='.$parent->getId();
    return '<li class="sf_admin_action_list">'.link_to(__($params['label'], array(), 'sf_admin'), '@sf_filebase_gallery'.$arguments).'</li>';
  }
}
