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

/**
 * Iterator for traversing filesystem structure, which automatically
 * filters out dots.
 *
 * @see        RecursiveDirectoryIterator
 */
class sfFilebasePluginRecursiveDirectoryIterator extends RecursiveDirectoryIterator
{
  /**
   * @var sfFilebasePlugin $filebase
   */
  protected $filebase;

  /**
   * Constructor
   *
   * @param sfFilebasePluginFile $path
   * @param integer $flags
   */
  public function __construct(sfFilebasePluginFile $path, $flags=parent::CURRENT_AS_FILEINFO)
  {
    //parent::setInfoClass('sfFilebasePluginFile');
    parent::__construct($path, $flags);
    $this->filebase = $path->getFilebase();
  }

  /**
   * Returns true if file is valid.
   * Valid Files: Directories and Files not beginning with a "." (dot).
   *
   * @todo make it more flexible to handle hidden dirs on demand etc,
   * win/*nix compat
   * 
   * @return boolean if current file entry is vali
   */
  public function valid()
  {
    if(parent::valid())
    {
      if (parent::isDot() || (parent::isDir() && strpos(parent::getFilename(), '.') === 0))
      {
        parent::next(); // zum nächsten eintrag hüpfen
        return $this->valid(); // nochmal prüfen
      }
      return true;
    }
    return false;
  }

  public function current()
  {
    $file = parent::current();
    if($file instanceof SplFileInfo)
    {
      // Wrap into sfFilebasePluginFile to provide additional methods
      // for analyzing file-type
      $file = new sfFilebasePluginFile($file, $this->filebase);
      if($file->isDir())
      {
        $file = new sfFilebasePluginDirectory($file, $this->filebase);
      }
      elseif($file->isImage())
      {
        $file = new sfFilebasePluginImage($file, $this->filebase);
      }
    }
    return $file;
  }
  
  /**
   * Returns childnodes
   *
   * @return sfFilebasePluginRecursiveDirectoryIterator $iterator
   */
  public function getChildren()
  {
    return new sfFilebasePluginRecursiveDirectoryIterator(self::current());
  }
}