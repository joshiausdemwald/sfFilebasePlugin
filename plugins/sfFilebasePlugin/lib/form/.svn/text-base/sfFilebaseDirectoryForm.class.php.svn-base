<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
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
