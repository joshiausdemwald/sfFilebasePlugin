<?php

/**
 * sfFilebaseDirectory form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class sfFilebaseDirectoryForm extends BasesfFilebaseDirectoryForm
{
  public function configure()
  {
    $file = null;
    if(!$this->isNew())
    {
      $file = $this->getObject()->getSfFilebasefile();
    }
    $file_form = new sfFilebaseFileForm($file);
    $this->mergeForm($file_form);
    unset($this['sf_filebase_files_id']);
    unset($this['sf_filebase_directories_id']);
    unset($this['sf_filebase_files_hash']);
    unset($this['sf_filebase_files_file']);
  }
}
