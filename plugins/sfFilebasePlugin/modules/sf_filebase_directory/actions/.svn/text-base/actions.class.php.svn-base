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

require_once dirname(__FILE__).'/../lib/sf_filebase_directoryGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sf_filebase_directoryGeneratorHelper.class.php';

/**
 * sf_filebase_directory actions.
 *
 * @package    test
 * @subpackage sf_filebase_directory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sf_filebase_directoryActions extends autoSf_filebase_directoryActions
{
  public function executeEdit(sfWebRequest $request)
  {
    $this->sf_filebase_directory = $this->getRoute()->getObject();
    $this->forward404if($this->sf_filebase_directory->getNode()->isRoot());
    $this->form = $this->configuration->getForm($this->sf_filebase_directory);
  }

  public function executeNew(sfWebRequest $request)
  {
    parent::executeNew($request);
    if(($source = $this->getUser()->getAttribute('source_node', null, 'sf_filebase_plugin')) !== null)
    {
      $this->form->setDirectoryPid($source->getId());
    }
  }

  public function buildQuery()
  {
    $query = parent::buildQuery();

    $query->leftJoin('r.rootNode d');
    $query->addWhere('d.environment=? AND d.application=?', array(
      sfConfig::get('sf_environment'),
      sfConfig::get('sf_app')
    ));
    return $query;
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('sf_filebase_gallery', 'index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    $parent = $this->getRoute()->getObject()->getNode()->getParent();

    $this->getRoute()->getObject()->getNode()->delete();

    $this->getUser()->setFlash('notice', 'The item was deleted successfully.');

    $this->redirect('@sf_filebase_directory?id='.$parent->getId());
  }
}
