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
class PluginsfFilebaseDirectoryTable extends sfAbstractFileTable
{
  public static function getChoices()
  {
    $table = Doctrine::getTable('sfFilebaseDirectory');
    $root = $table->getRootNode();
    $tree_object = Doctrine::getTable('sfFilebaseDirectory')->getTree();
    $tree = $tree_object->fetchTree(array('root_id'=>$root->getId()));
    if(!$tree)
    {
      $root = new sfFilebaseDirectory();
      $root->setFilename(sfFilebasePlugin::getInstance()->getFilename());
      $root->setHash(sfFilebasePlugin::getInstance()->getHash());
      $root->save();
      $tree_object->createRoot($root);
      $tree = $tree_object->fetchTree();
    }
    foreach($tree AS $dir)
    {
      if($dir->getNode()->isRoot())
      {
        $directory_choices[$dir['id']] = str_repeat('&nbsp;&nbsp;', $dir['level']) . '/';
      }
      else
      {
        $directory_choices[$dir['id']] = str_repeat('&nbsp;&nbsp;', $dir['level']) . $dir['filename'];
      }
    }
    return $directory_choices;
  }

  /**
   * Retrieves the root node of the doctrine
   * filebase tree by application and 
   * environment parameters
   *
   * @param Doctrine_Connection $conn
   * @return sfFilebaseDirectory $d
   */
  public function getRootNode($environment = null, $application = null, $conn = null)
  {
    $app = $application === null ? sfConfig::get('sf_app') : $application;
    $env = $environment === null ? sfConfig::get('sf_environment') : $environment;
    return Doctrine_Query::create($conn)->
           select('*')->
           from('sfFilebaseDirectory d')->
           where('d.environment=? AND d.application = ? AND d.lft=1')->
           execute(array($env, $app))->get(0);
  }
}