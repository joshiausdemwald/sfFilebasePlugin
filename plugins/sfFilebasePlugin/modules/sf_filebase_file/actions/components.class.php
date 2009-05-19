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

class sf_filebase_fileComponents extends sfComponents
{
  public function executeRetrieveFiles(sfWebRequest $request)
  {
    $f = sfFilebasePlugin::getInstance();
    $this->is_edit = false;
    if($this->form)
    {
      $this->is_edit = true;
      $this->file = $f[$this->form->getObject()->getPathname()];
    }
    elseif($this->sf_filebase_file)
    {
      $this->file = $f[$this->sf_filebase_file->getPathname()];
    }
    else
      return sfView::NONE;
  }
}