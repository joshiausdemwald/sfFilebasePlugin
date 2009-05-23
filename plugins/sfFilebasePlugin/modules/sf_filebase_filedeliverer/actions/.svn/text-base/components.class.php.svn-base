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
class sf_filebase_filedelivererComponents extends sfComponents
{
  public static $module_counter = 0;
  
  public function executeDirectoryTree(sfWebRequest $request)
  {
    if($this->root === null)
    {
      $this->root = Doctrine::getTable('sfFilebaseDirectory')->getRootNode();
    }
    if(! $this->root instanceof sfFilebaseDirectory)
    {
      return sfView::NONE;
    }
    $this->id = 'Sf_Filebase_Tree_' . self::$module_counter;
    self::$module_counter ++;
  }
}