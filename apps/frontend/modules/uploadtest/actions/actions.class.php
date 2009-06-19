<?php

/**
 * uploadtest actions.
 *
 * @package    test
 * @subpackage uploadtest
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class uploadtestActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new UploadTestForm();
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('uploadtest'), $request->getFiles('uploadtest'));
      if($this->form->isValid())
      {
        foreach($this->form->getValue('files') AS $file)
        {
          if($file instanceof sfFilebasePluginUploadedFile)
            $file->moveUploadedFile('');
        }
        $this->getUser()->setFlash('notice', 'Everything went fine.');
        $this->redirect('uploadtest/index');
      }
    }
  }
}
