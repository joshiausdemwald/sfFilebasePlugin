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
    $filebase = sfFilebasePlugin::getInstance();
    $this->upload_form = new UploadForm();
    $this->create_directory_form = new CreateDirectoryForm();

    $this->message = $this->getUser()->getFlash('message', null);
    
    if($request->isMethod('post'))
    {
      $upload_data           = $request->getParameter('upload', null);
      $create_directory_data = $request->getParameter('directory', null);
      if($upload_data !== null)
      {
        
        $filebase = sfFilebasePlugin::getInstance();

        // This does work, if you define sfFilebasePluginWebRequest as the
        // default request-instance in apps/myapp/config/factories.yml
        //
        // If you dont want to do so, use
        // $filebase->getUploadedFiles('upload')
        $this->upload_form->bind($upload_data, $request->getUploadedFiles('upload'));
        
        if($this->upload_form->isValid())
        {
          $files = $this->upload_form->getValue('files');
          foreach($files AS $file)
          {
            $file = $file->moveUploadedFile($filebase->getFilebaseFile((string)$this->upload_form->getValue('directory')));
            if($file instanceof sfFilebasePluginImage)
            {
              $file->rotate('20');
            }
          }
          $this->getUser()->setFlash('message', 'Files uploaded. Images slightly rotated because it makes no sense.');
          $this->redirect('start/index');
        }
      }
      if($create_directory_data !== null)
      {
        $this->create_directory_form->bind($create_directory_data);
        if($this->create_directory_form->isValid())
        {
          $directory = $this->create_directory_form->getValue('directory');
          $name      = $this->create_directory_form->getValue('name');
         
          $dirname = $filebase->getPathname() . '/' . $directory . '/' . $name;
          if($filebase->getFileExists($dirname))
          {
            $this->error = "Directory already exists";
          }
          else
          {
            $this->getUser()->setFlash('message', 'Directory created.');
            $filebase->mkDir($dirname, 0777);
            $this->redirect('start/index');

          }
        }
      }
    }
  }

  public function executeDelete(sfwebRequest $request)
  {
    $hash = $request->getParameter('f', null);
    $this->forward404If($hash === null);
    $filebase = sfFilebasePlugin::getInstance();
    $file = $filebase->getFileByHash($hash);
    $this->forward404Unless($file instanceof sfFilebasePluginFile);
    if($file instanceof sfFilebasePluginDirectory && ! $file->isEmpty())
    {
      $this->getUser()->setFlash('message', 'Directory not empty. Use sfFilebasePluginDirectory::delete(true) or sfFilebasePluginDirectory::deleteRecursive() to delete dir with containing files.');
      $this->redirect('start/index');
    }
    $file->delete();
    $this->getUser()->setFlash('message', 'File successfully deleted.');
    $this->redirect('start/index');
  }
}
