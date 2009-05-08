<?php
/**
 *
 * @author    Joshi
 * @copyright Joshi
 * @package   de.optimusprime.util.filebase
 */
class FilebaseDirectory extends FilebaseFile implements IteratorAggregate, ArrayAccess, Countable
{
  /**
   * Tries to delete the directory from fs,
   * also deletes all children.
   *
   * @return boolean true if deletion was successful
   */
  public function deleteRecursive()
  {
    if(!$this->isEmpty())
    {
      foreach($this AS $file)
      {
        // @todo check against class, not retval
        if($file->isDir())
        {
          $file->deleteRecursive();
        }
        else
        {
          $file->delete();
        }
      }
    }
    return $this->delete();
  }

  /**
   * Trys to delete the directory from fs.
   *
   * @throws FilebaseException
   * @return boolean true if deletion was successful
   */
  public function delete()
  {
    return $this->filebase->deleteDirectory($this);
  }

  /**
   * Changes the access permissions of a FilebaseDirectory,
   * including all sub-files and directories.
   *
   * @param integer
   * @throws FilebaseException
   * @return FilebaseDirectory $file
   */
  public function chmodRecursive($perms = 0755)
  {
    foreach($this AS $file)
    {
      if($file->isDir())
      {
        $file->chmodRecursive($perms);
      }
      else
      {
        $file->chmod($perms);
      }
    }
    return parent::chmod($perms);
  }

  /**
   * Wraps a FilebaseDirectory around the return
   * value of FilebaseFile::rename() to handle
   * the proper instance.
   *
   * @param mixed FilebaseFile | string $path_name
   * @return FilebaseDirectory $dir
   */
  public function rename($path_name, $overwrite = true)
  {
    return new FilebaseDirectory(parent::rename($path_name, $overwrite), $this->getFilebase());
  }

  /**
   * Copies this folder to target destination
   *
   * @param mixed FilebaseFile | string $target
   */
  public function copy($target, $overwrite=false)
  {
    return new FilebaseDirectory(parent::copy($target, $overwrite));
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
   * filebase Directory
   *
   * @return RecursiveDirectoryIterator $iterator
   */
  public function getIterator()
  {
    return new RecursiveFilebaseIterator($this);
  }

  /**
   * Opens this File, overwriting splFileInfo::openFile().
   * Returns an array with FilebaseFileObject for each
   * file the directory contains.
   *
   * Useful (if though) for batch processing.
   *
   * @todo   check if this method is nonsense ;)
   * @return RecursiveFilebaseIterator
   */
  public function openFile($open_mode = 'r', $use_include_path = false, $context = null)
  {
    $open_files = array();
    foreach($this AS $file)
    {
      if(!$file instanceof FilebaseDirectory)
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
   * @throws FilebaseException
   * @param string $offset
   */
  public function offsetGet($offset)
  {
    $file = $this->getFilebaseFile($offset);
    if($file->fileExists())
    {
      return $file;
    }
    throw new FilebaseException (sprintf('File %s does not exist in directory %s.', $file->getPathname(), $this->getPathname()));
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
    if($offset->fileExists()) throw new FilebaseException('File %s cannot be created on the fly: Destination already exists.', $offset->getPathname());

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
   * @throws FilebaseException
   */
  public function isEmpty()
  {
    if(!$this->fileExists())  throw new FilebaseException(sprintf('File %s does not exist.', $this->getPathname()));
    if(!$this->isReadable())  throw new FilebaseException(sprintf('File %s is read protected.', $this->getPathname()));
    foreach($this AS $file) // excludes dots
    {
      return false;
    }
    return true;
  }
}