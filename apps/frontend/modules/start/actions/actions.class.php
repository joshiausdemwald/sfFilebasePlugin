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

    $this->filebase = new sfFilebasePlugin();
    $this->upload_form = new UploadForm();
    $this->create_directory_form = new CreateDirectoryForm();

    $this->message = $this->getUser()->getFlash('message', null);
    
    if($request->isMethod('post'))
    {
      $upload_data           = $request->getParameter('upload', null);
      $create_directory_data = $request->getParameter('directory', null);
      if($upload_data !== null)
      {
        
        $filebase = new sfFilebasePlugin();

        $this->upload_form->bind($upload_data, $filebase->getUploadedFiles('upload'));
        
        if($this->upload_form->isValid())
        {
          $files = $this->upload_form->getValue('files');
          foreach($files AS $file)
          {
            $file = $file->moveUploadedFile($this->filebase->getFilebaseFile((string)$this->upload_form->getValue('directory')));
            if($file instanceof sfFilebasePluginImage)
            {
              $file->rotate('20');
              $this->getUser()->setFlash('message', 'Image uploaded and rotated a bit.');
            }
            else
            {
              $this->getUser()->setFlash('message', 'File uploaded.');
            }
            $this->redirect('start/index');
          }
        }
      }
      if($create_directory_data !== null)
      {
        $this->create_directory_form->bind($create_directory_data);
        if($this->create_directory_form->isValid())
        {
          $directory = $this->create_directory_form->getValue('directory');
          $name      = $this->create_directory_form->getValue('name');
         
          $dirname = $this->filebase->getPathname() . '/' . $directory . '/' . $name;
          if($this->filebase->getFileExists($dirname))
          {
            $this->error = "Directory already exists";
          }
          else
          {
            $this->getUser()->setFlash('message', 'Directory created.');
            $this->filebase->mkDir($dirname);
            $this->redirect('start/index');

          }
        }
      }
    }
    //$this->filebase = new sfFilebasePlugin();
    
    //$files = $this->filebase->moveAllUploadedFiles($this->filebase->getPathname());
    //$this->images = array();
    /*foreach($files AS $file)
    {
      if($file instanceof sfFilebasePluginImage)
      {
        $file->rotate(30);
        $this->images[] = $file->getThumbnail(array(500));
      }
    }*/
    
    //$this->hamburg_bilder = array();
    //foreach($this->filebase AS $file)
    //  if($file instanceof sfFilebasePluginImage)
    //    $this->hamburg_bilder[] = $file;
  }
}
