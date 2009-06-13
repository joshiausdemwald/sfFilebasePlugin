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

require_once dirname(__FILE__).'/../lib/sf_filebase_fileGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sf_filebase_fileGeneratorHelper.class.php';

/**
 * sf_filebase_file actions.
 *
 * @package    test
 * @subpackage sf_filebase_file
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sf_filebase_fileActions extends autoSf_filebase_fileActions
{
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

  public function executeShow()
  {
    $this->forward('sf_filebase_overview', 'index');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->sf_filebase_file = $this->form->getObject();
    $ret = $this->processForm($request, $this->form);
    if($request->hasParameter('swfupload_filesource'))
    {
      sfConfig::set('sf_web_debug', false);
      $this->setLayout(false);
      $this->setTemplate('swfupload');
    }
    else
    {
      $this->setTemplate('new');
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      // kommt vom multiupload-gedingse
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      $sf_filebase_file = $form->save();

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $sf_filebase_file)));

      if(!$request->hasParameter('swfupload_filesource'))
      {
        if ($request->hasParameter('_save_and_add'))
        {
          $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

          $this->redirect('@sf_filebase_file_new');
        }
        else
        {
          $this->getUser()->setFlash('notice', $notice);

          $this->redirect(array('sf_route' => 'sf_filebase_file_edit', 'sf_subject' => $sf_filebase_file));
        }
      }
      return true;
    }
    else
    {
      if(!$request->hasParameter('swfupload_filesource'))
      {
        $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
      }
      return false;
    }
  }
}
