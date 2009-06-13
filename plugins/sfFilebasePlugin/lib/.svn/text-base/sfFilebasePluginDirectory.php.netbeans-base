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
 * sfFilebasePlugin directory represents a directory
 *
 * @see        SplFileInfo
 */
class sfFilebasePluginDirectory extends sfFilebasePluginFile implements IteratorAggregate, ArrayAccess, Countable
{
  /**
   * Trys to delete the directory from fs.
   *
   * @throws sfFilebasePluginException
   * @see sfFilebasePlugin::deleteDirectory()
   * @param boolean $ignore_contents: If set to true, dir is deleted
   *                                  despite of its contents.
   * @return boolean true if deletion was successful
   */
  public function delete($ignore_contents = false)
  {
    return $this->filebase->deleteDirectory($this, $ignore_contents);
  }

  public function chmod($perms = 0755, $recursive = true)
  {
    $chmod = parent::chmod($perms);
    if($recursive)
    {
      foreach($this AS $child)
      {
        $child->chmod($perms, true);
      }
    }
    return $chmod;
  }

  /**
   * Wraps a sfFilebasePluginDirectory around the return
   * value of sfFilebasePluginFile::rename() to handle
   * the proper instance.
   *
   * @param mixed sfFilebasePluginFile | string $path_name
   * @return sfFilebasePluginDirectory $dir
   */
  public function rename($path_name, $overwrite = false)
  {
    return new sfFilebasePluginDirectory(parent::rename($path_name, $overwrite), $this->getFilebase());
  }

  /**
   * Copies this folder to target destination
   *
   * @param mixed $allow_overwrite: If true, existing destination files may be
   *                                overwritten.
   * @param mixed sfFilebasePluginFile | string $destination
   */
  public function copy($destination, $allow_overwrite=false)
  {
    return $this->filebase->copyDirectory($this, $destination, $allow_overwrite);
  }

  /**
   * Calculates size of the directory,
   * cumulating every sub-file and dir.
   *
   * @return integer | string (formatted)
   */
  public function getSize($format = null)
  {
    $size = 0;
    foreach($this AS $file)
    {
      $size += $file->getSize();
    }
    return $size;
  }
  
  /**
   * Returns RecursiveDirectoryIterator of
   * sfFilebasePlugin Directory
   *
   * @return sfFilebasePluginRecursiveDirectoryIterator $iterator
   */
  public function getIterator()
  {
    return new sfFilebasePluginRecursiveDirectoryIterator($this);
  }

  /**
   * Opens this File, overwriting splFileInfo::openFile().
   * Returns an array with sfFilebasePluginFileObject for each
   * file the directory contains.
   *
   * Useful (if though) for batch processing.
   *
   * @todo   check if this method is nonsense ;)
   * @return sfFilebasePluginRecursiveDirectoryIterator
   */
  public function openFile($open_mode = 'r', $use_include_path = false, $context = null)
  {
    $open_files = array();
    foreach($this AS $file)
    {
      if(!$file instanceof sfFilebasePluginDirectory)
      {
        $open_files[] = $file->openFile($open_mode, $use_include_path, $context);
      }
    }
    return $open_files;
  }

  /**
   * Implements ArrayAccess
   *
   * @see ArrayAccess
   * @param string $offset
   */
  public function offsetExists($offset)
  {
    return $this->getFilebaseFile($offset)->fileExists();
  }

  /**
   * Implements ArrayAccess
   *
   * @see ArrayAccess
   * @throws sfFilebasePluginException
   * @param string $offset
   */
  public function offsetGet($offset)
  {
    $file = $this->getFilebaseFile($offset);
    if($file->fileExists())
    {
      return $file;
    }
    throw new sfFilebasePluginException (sprintf('File %s does not exist in directory %s.', $file->getPathname(), $this->getPathname()));
  }

  /**
   * Implements ArrayAccess
   *
   * @see ArrayAccess
   * @param string $offset
   */
  public function offsetUnset($offset)
  {
    $file = $this->offsetGet($offset);
    $file->delete();
  }

  /**
   * Implements ArrayAccess
   *
   * @see ArrayAccess
   * @param string $offset
   */
  public function offsetSet($offset, $value)
  {
    $offset = $this->getFilebaseFile($offset);
    if($offset->fileExists()) throw new sfFilebasePluginException('File %s cannot be created on the fly: Destination already exists.', $offset->getPathname());

    // Analyze value
    //if($offset)
  }

  /**
   * Implements Countable
   *
   * @see Countable
   * @param string $offset
   */
  public function count()
  {
    // glob*.* would return dots, too, so use iterator
    $n = 0;
    foreach($this AS $entry)
    {
      $n++;
    }
    return $n;
  }

  /**
   * Returns a human readable string file type.
   * This could be for example "jpeg image" or "wma audio"
   *
   * @return string
   */
  public function getHumanReadableFileType()
  {
    return 'directory';
  }

  /**
   * Returns true if file is a directory and empty
   *
   * @return boolean true if file is an empty dir
   * @throws sfFilebasePluginException
   */
  public function isEmpty()
  {
    if(!$this->fileExists())  throw new sfFilebasePluginException(sprintf('File %s does not exist.', $this->getPathname()));
    if(!$this->isReadable())  throw new sfFilebasePluginException(sprintf('File %s is read protected.', $this->getPathname()));
    foreach($this AS $file) // excludes dots
    {
      return false;
    }
    return true;
  }
}