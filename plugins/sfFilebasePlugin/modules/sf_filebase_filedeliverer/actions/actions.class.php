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
    $hash = $request->getParameter('file', null);
    $this->forward404If($hash === null);
    $f = sfFilebasePlugin::getInstance();
    $file = $f->getFileByHash($hash);
    $this->forward404If($file === null);

    $this->setLayout(false);
    $this->getResponse()->setContentType($file->getMimeType('application/octet-stream'));
    $this->getResponse()->setHttpHeader('Content-Tranfer-Encoding', 'binary');
    $this->getResponse()->setHttpHeader('Content-Length', $file->getSize());
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $file->getFilename() . ' size=' . $file->getSize());
    $this->getResponse()->setContent($file->openFile('r+')->fileGetContents());
    return sfView::NONE;
  }

  public function executeDisplay_image(sfWebRequest $request)
  {
    $hash = $request->getParameter('file', null);
    $this->forward404If($hash === null);
    $f = sfFilebasePlugin::getInstance();
    $file = $f->getFileByHash($hash)->getThumbnail(array($request->getParameter('width', null), $request->getParameter('height', null)));
    $this->forward404If($file === null);

    $this->setLayout(false);
    $this->getResponse()->setContentType($file->getMimeType('application/octet-stream'));
    $this->getResponse()->setHttpHeader('Content-Tranfer-Encoding', 'binary');
    $this->getResponse()->setHttpHeader('Content-Length', $file->getSize());
    //$this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=' . $file->getFilename() . ' size=' . $file->getSize());
    $this->getResponse()->setContent($file->openFile('r+')->fileGetContents());
    return sfView::NONE;
  }
}
