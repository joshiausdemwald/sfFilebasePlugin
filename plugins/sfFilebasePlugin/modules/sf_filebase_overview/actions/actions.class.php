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
    $id = $request->getParameter('id', null);
    $root = null;
    if($id)
    {
      $root = $table->find($id);
    }
    else
    {
      $root = $table->getRootNode();
    }
    $this->forward404Unless($root instanceof sfFilebaseDirectory);
    $this->parent = $root;
  }
}
