<?php

/**
 * start actions.
 *
 * @package    test
 * @subpackage start
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class startActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    /*sfConfig::set('cms_module_dir',sfConfig::get('sf_root_dir').'/modules');
    $mm = ModuleManager::instance($this->getContext());
    $mm->load(sfConfig::get('cms_module_dir'));
    $mm->checkDependancies();*/

    $this->filebase = new Filebase();
    $this->filebase->clearCache();
    $files = $this->filebase->moveAllUploadedFiles($this->filebase->getPathname());
    $this->images = array();
    foreach($files AS $file)
    {
      if($file instanceof FilebaseImage)
      {
        $this->images[] = $file->getThumbnail(array(500));
      }
    }
  }
}
