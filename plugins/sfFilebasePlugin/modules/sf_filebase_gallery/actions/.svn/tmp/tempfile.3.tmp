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
class sf_filebase_galleryActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->filter = new sfFilebaseFileFormFilter();
    
    $this->tag = $request->getParameter('tag');
    if($this->tag == '___')
    {
      $this->getUser()->setAttribute('tag', null, 'sf_filebase_gallery');
      $this->tag = null;
    }
    else
    {
      if(empty($this->tag))
      {
        $this->tag = $this->getUser()->getAttribute('tag', null, 'sf_filebase_gallery');
      }
      else
      {
        $this->getUser()->setAttribute('tag', $this->tag, 'sf_filebase_gallery');
      }
    }

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
    $this->getUser()->setAttribute('source_node', $root, 'sf_filebase_plugin');
    $this->parent = $root;
  }
}
