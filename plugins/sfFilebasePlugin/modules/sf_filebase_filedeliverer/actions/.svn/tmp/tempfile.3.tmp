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
class sf_filebase_filedelivererActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  public function executeDownload(sfWebRequest $request)
  {
    $id = $request->getParameter('file', null);
    $this->forward404If($id === null);
    $f = sfFilebasePlugin::getInstance();
    $file_object = Doctrine::getTable('sfFilebaseFile')->find($id);
    $this->forward404Unless($file_object);
    $file = $f->getFilebaseFile($file_object->getHash());
    $this->forward404If($file === null);

    $this->setLayout(false);
    $this->getResponse()->setContentType($file->getMimeType('application/octet-stream'));
    $this->getResponse()->setHttpHeader('Content-Tranfer-Encoding', 'binary');
    $this->getResponse()->setHttpHeader('Content-Length', $file->getSize());
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $file_object->filename . ' size=' . $file->getSize());
    $this->getResponse()->setContent($file->openFile('r+')->fileGetContents());
    return sfView::NONE;
  }

  public function executeDisplay_image(sfWebRequest $request)
  {
    $id = $request->getParameter('file', null);
    $this->forward404If($id === null);
    $f = sfFilebasePlugin::getInstance();
    $file_object = Doctrine::getTable('sfFilebaseFile')->find($id);
    $file = $f->getFilebaseFile($file_object->getHash())->getThumbnail(array('width'=>$request->getParameter('width', null), 'height'=>$request->getParameter('height', null)));
    $this->forward404If($file === null);

    $this->setLayout(false);
    $this->getResponse()->setContentType($file->getMimeType('application/octet-stream'));
    $this->getResponse()->setHttpHeader('Content-Tranfer-Encoding', 'binary');
    $this->getResponse()->setHttpHeader('Content-Length', $file->getSize());
    //$this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $file->getFilename() . ' size=' . $file->getSize());
    $this->getResponse()->setContent($file->openFile('r+')->fileGetContents());
    return sfView::NONE;
  }

  public function executeMoveFile(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());

    $node_id        = $request->getParameter('node', null);
    $parent_id      = $request->getParameter('parent', null);
    $is_leaf        = $request->getParameter('leaf', null);
    $this->forward404If($node_id === null || $parent_id === null || $is_leaf === null);

    $node = null;
    if($is_leaf)
    {
      $node   = Doctrine::getTable('sfFilebaseFile')->find($node_id);
    }
    else
    {
      $node   = Doctrine::getTable('sfFilebaseDirectory')->find($node_id);
    }
    $parent = Doctrine::getTable('sfFilebaseDirectory')->find($parent_id);

    $this->forward404Unless($node instanceof sfAbstractFile && $parent instanceof sfFilebaseDirectory);
    
    $node->getNode()->moveAsLastChildOf($parent);
    
    $this->setLayout(false);
    $this->response->setContent('1');
    $this->response->setContentType('text/plain');
    return sfView::NONE;
  }

  public function executeGetTree(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());
    $id   = $request->getParameter('node');
    $this->root = Doctrine::getTable('sfFilebaseDirectory')->find($id);
    $this->forward404Unless($this->root instanceof sfFilebaseDirectory);
    $this->setLayout(false);
    $this->getResponse()->setContentType('text/x-json');
  }
}
