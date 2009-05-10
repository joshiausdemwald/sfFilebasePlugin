<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Iterator for traversing filesystem structure, which automatically
 * filters out dots.
 *
 * @see        RecursiveDirectoryIterator
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class RecursiveFilebaseIterator extends RecursiveDirectoryIterator
{
  /**
   * @var Filebase $filebase
   */
  protected $filebase;

  /**
   * Constructor
   *
   * @param FilebaseFile $path
   * @param integer $flags
   */
  public function __construct(FilebaseFile $path, $flags=parent::CURRENT_AS_FILEINFO)
  {
    //parent::setInfoClass('FilebaseFile');
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
        //echo parent::current() . parent::isDir() . strpos(parent::getFilename(),'.') . "<br/>";
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
      // Wrap into FilebaseFile to provide additional methods
      // for analyzing file-type
      $file = new FilebaseFile($file, $this->filebase);
      if($file->isDir())
      {
        $file = new FilebaseDirectory($file, $this->filebase);
      }
      elseif($file->isImage())
      {
        $file = new FilebaseImage($file, $this->filebase);
      }
    }
    return $file;
  }
  
  /**
   * Returns childnodes
   *
   * @return RecursiveFilebaseIterator $iterator
   */
  public function getChildren()
  {
    return new RecursiveFilebaseIterator(self::current());
  }
}