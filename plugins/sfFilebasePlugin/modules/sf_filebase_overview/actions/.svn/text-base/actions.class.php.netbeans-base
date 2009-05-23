<?php

/**
 * sf_filebase_overview actions.
 *
 * @package    test
 * @subpackage sf_filebase_overview
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class sf_filebase_overviewActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $table = Doctrine::getTable('sfFilebaseDirectory');
    $root = $table->getRootNode();
    $tree_object = $table->getTree();
    $this->tree = $tree_object->fetchTree(array('root_id'=>$root->getId()));
    $this->root = $root;
  }
}
