<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Filebase provides easy access to file system in oop style
 *
 * @see        SplFileInfo
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class Filebase extends FilebaseDirectory
{
  /**
   * FileInfo Object of Cache Directory
   *
   * @var FilebaseFile $cacheDirectory
   */
  protected $cacheDirectory;

  /**
   * @var UploadedFilesManager $uploadedFilesManager
   */
  protected $uploadedFilesManager;
  
  /**
   * @var GfxEditor
   */
  protected $gfxEditor;

  /**
   * Constructor. Parameters are filebase-"root"-directory and cache-dir for
   * e.g. temp-files and thumbnails.
   *
   * @param   string $cache_directory:    Cache directory may explicitely be
   *                                      specified. Consider that the default
   *                                      directory is accessible via browser.
   *                                      Default is a subdirectory of
   *                                      sfConfig::get('sf_upload_dir')
   * @param   string $filebaseDirectory:  Filebase directory may explicetly be
   *                                      specified. Consider that the default
   *                                      directory is accessible via browser.
   *                                      Default is sfConfig::get('sf_upload_dir')
   * @throws  FilebaseException
   * @param   string $mode
   */
  function __construct($path_name = null, $cache_directory = null)
  {
    $path_name === null && $path_name = sfConfig::get('sf_upload_dir', null);

    parent::__construct($path_name, $this);

    if(!$this->fileExists())    throw new FilebaseException(sprintf('Filebase directory %s does not exist.', $this->getPathname()));
    if(!$this->isDir())         throw new FilebaseException(sprintf('File %s is not a directory', $this->getPathname()));
    if(!$this->isReadable())    throw new FilebaseException(sprintf('File %s is not readable', $this->getPathname()));
    // Filebase root must have read/write-access.
    if(!$this->isWritable())   throw new FilebaseException(sprintf('File %s is read or write protected', $this->getPathname()));
    
    // Initialize cache.
    $this->initCache($cache_directory);
    $this->uploadedFilesManager = new UploadedFilesManager($this);
    $this->gfxEditor           = new GfxEditor($this);
  }

    /**
   * Checks if CacheDirectory exists, if
   * not, then create it.
   *
   * @param $cache_directory Path to cache_dir.
   * @throws FilebaseException
   * @return FilebaseFile $cache_directory
   */
  protected function initCache($cache_directory = null)
  {
    $this->cacheDirectory = $this->getFilebaseFile('.'.md5(get_class()));

    if(!$this->cacheDirectory->fileExists())
    {
      self::mkDir($this->cacheDirectory, 0777);
    }
    return $this->cacheDirectory;
  }
  
  /**
   * Returns cache directory for this filebase.
   * 
   * @return FilebaseDirectory $cacheDirectory
   */
  public function getCacheDirectory()
  {
    return $this->cacheDirectory;
  }

  /**
   * Clears the cache directory
   *
   */
  public function clearCache()
  {
    foreach($this->cacheDirectory AS $file)
    {
      if($file->isDir())
      {
        $file->deleteRecursive();
      }
      else
      {
        $file->delete();
      }
    }
    return true;
  }

  /**
   * Returns an unique hash value representation
   * of the given pathname, relative from filebaseDirectory.
   *
   * @param mixed FilebaseFile | string $file
   * @return string $hash_value
   */
  public function getHashForFile($file)
  {
    $file = $this->getFilebaseFile ($file);
    if(!$this->getFilebase()->isInFilebase($file)) throw new FilebaseException('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $file->getPathname(), $this->getPathname());
    return md5($file->getPathname());
  }

  /**
   * Copies a file to the given destination.
   *
   * @param mixed FilebaseFile | string $source: The source file
   * @param mixed FilebaseFile | string $destination: The destination file
   * @param boolean $overwrite: If true dest file will be overwritten
   * @return FilebaseFile $copied_file
   */
  public function copyFile($source, $destination, $overwrite = true)
  {
    $source           = $this->getFilebaseFile($source);
    $destination      = $this->getFilebaseFile($destination);
    $destination_dir  = new FilebaseDirectory($destination->getPath(), $this);

    if(!$source->fileExists()) throw new FilebaseException(sprintf('Error copying file %s: File does not exist.', $source->getPathname()));
    if(!$source->isReadable()) throw new FilebaseException(sprintf('Error copying file %s: Source is read protected.', $source->getPathname()));
    
    // Only check target. Files may be copied from any location.
    if(!$this->isInFilebase($destination))   throw new FilebaseException('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname());
    if($destination->fileExists())
    {
      if($overwrite)
      {
        if(!$destination->isWritable()) throw new FilebaseException(sprintf('Destination file %s is write protected.', $destination->getPathname()));
      }
      else throw new FilebaseException(sprintf('Error copying file %s into %s: Target already exists.', $source->getPathname(), $destination->getPathname()));
    }
    else
    {
      if(!$destination_dir->isWritable()) throw new FilebaseException(sprintf('Error copying file %s: Target folder %s is write protected.', $destination->getPathname(), $destination_dir->getPathname()));
    }
   
    if(!@copy($source->getPathname(), $destination->getPathname()))
    {
      throw new FilebaseException(sprintf('Error copying file %s to %s: %s', $source->getPathname(), $destination->getPathname(), implode("\n", error_get_last())));
    }
    return $destination;
  }

  /**
   * Renames a file.
   *
   * @param mixed FilebaseFile | string $source:      The source file-name
   * @param mixed FilebaseFile | string $destination: The target file-name
   * @throws FilebaseException
   * @return FilebaseFile $file
   */
  public function renameFile($source, $new_name, $overwrite = true)
  {
    $source       = $this->getFilebaseFile($source);
    $destination  = $this->getFilebaseFile($source->getPath() . '/' . $new_name);
    if(!$this->isInFilebase($source))         throw new FilebaseException('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $source->getPathname(), $this->getFilebase()->getPathname());
    if(!$this->isInFilebase($destination))    throw new FilebaseException('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname());

    if(!$source->fileExists())  throw new FilebaseException(sprintf('Error renaming file %s: File does not exist.', $source->getPathname()));
    if(!$source->isWritable())  throw new FilebaseException(sprintf('Error renaming file %s: Write protected.', $source->getPathname()));
    if($destination->fileExists())
    {
      if($overwrite)
      {
        if(!$destination->isWritable()) throw new FilebaseException(sprintf('Destination file %s exists but is write protected.', $destination->getPathname()));
      }
      else throw new FilebaseException(sprintf('Error renaming file %s into %s: Target already exists.', $source->getPathname(), $destination->getPathname()));
    }
    if(!@rename($source->getPathname(), $destination->getPathname()))
    {
      throw new FilebaseException(sprintf('Error renaming file %s to %s: %s', $source->getPathname(), $destination->getPathname(), implode("\n", error_get_last())));
    }
    return $destination;
  }

  /**
   * Trys to delete a file from fs.
   *
   * @param FilebaseFile | string $file
   * @throws FilebaseException
   */
  public function deleteFile($file)
  {

    if(!$file->fileExists()) throw new FilebaseException(sprintf('FilebaseFile %s does not exist.', $file->getPathname()));
    if(!$this->isInFilebase($file)) throw new FilebaseException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $file->getPathname(), $this->getPathname()));
    if(!$file->isWritable()) throw new FilebaseException(sprintf('File %s is write protected.', $file->getPathname()));

    if(!@unlink($file->getPathname()))
    {
      throw new FilebaseException(sprintf('Error while deleting file %s: %s', $file->getPathname(), implode("\n", error_get_last())));
    }
    return true;
  }

  /**
   * Trys to delete the directory from fs.
   *
   * @param mixed FilebaseFile | string $dir
   * @throws FilebaseException
   * @return boolean true if deletion was successful
   */
  public function deleteDirectory ($directory)
  {
    $directory = $this->getFilebaseFile($directory);
    if(!$this->isInFilebase($directory)) throw new FilebaseException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $directory->getPathname(), $this->getPathname()));
    if(!$directory->fileExists()) throw new FilebaseException(sprintf('Directory %s does not exist.', $directory->getPathname()));
    if(!$directory instanceof FilebaseDirectory) throw new FilebaseException(sprintf('File %s is not a directory.', $directory->getPathname()));
    if(!$directory->isWritable()) throw new FilebaseException(sprintf('Directory %s is write protected.', $directory->getPathname()));
    if(!$directory->isEmpty()) throw new FilebaseException(sprintf('Error deleting directory %s: Directory is not empty.', $directory->getPathname()));

    if(!@rmdir($directory->getPathname()))
    {
      throw new FilebaseException(sprintf('Error while deleting directory %s: %s', $directory->getPathname(), error_get_last()));
    }
    return true;
  }

  /**
   * Changes the access permissions of a FilebaseFile
   *
   * @param FilebaseFile | string: The file to chmod()
   * @param integer
   * @todo move it to filebase
   * @throws FilebaseException
   * @return FilebaseFile $file
   */
  public function chmodFile($destination, $perms = 0755)
  {
    if(!$destination->fileExists())         throw new FilebaseException(sprintf('FilebaseFile %s does not exist.', $destination->getPathname()));
    if(!$this->isInFilebase($destination))  throw new FilebaseException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname()));
    if(!$destination->isWritable())         throw new FilebaseException(sprintf('FilebaseFile %s is write protected.', $destination->getPathname()));
    if(!@chmod($destination, $perms))       throw new FilebaseException(sprintf('Error chmod-ing directory %s: %s', $destination->getPathname(), implode("\n", error_get_last())));
    return $destination;
  }

  /**
   * Creates a new directory. Throws exceptions if target is not
   * writable, dir already exists etc...
   *
   * @param mixed FilebaseFile | string $path
   * @throws FilebaseException
   * @return FilebaseFile $file
   */
  public function mkDir($path, $perms = 0755)
  {
    // Wrap around, because isDir() returs false on non-existing files.
    $path = new FilebaseDirectory(self::getFilebaseFile($path), $this);
    $dest = new FilebaseDirectory($path->getPath(), $this);
    if(!$dest->fileExists()) throw new FilebaseException(sprintf('Destination directory %s does not exist.', $dest->getPathname()));
    if(!$dest->isDir()) throw new FilebaseException (sprintf('Destination %s is not a directory.',$dest->getPathname()));
    if(!$dest->isWritable()) throw new FilebaseException(sprintf('Destination directory %s is write protected.', $dest->getPathname()));
    if(!$this->isInFilebase($dest)) throw new FilebaseException(sprintf('Destination directory %s does not belong to filebase %s, access forbidden due to security issues.', $dest->getPathname(), $this->getPathname()));
    if($path->fileExists()) throw new FilebaseException (sprintf('Directory %s already exists',$path->getPathname()));
    if(!@mkdir($path->getPathname())) throw new FilebaseException(sprintf('Error creating directory %s', $path->getPathname()), 2010);
    //  Chmodde dir
    $path->chmod($perms);
    return $path;
  }

  /**
   * Creates a new empty file using touch().
   *
   * @param mixed FilebaseFile | string $path
   * @param integer $perms
   * @return FilebaseFile $new_file
   */
  public function touch($path, $perms = 0755)
  {
     // Wrap around, because isDir() returs false on non-existing files.
    $path = new FilebaseFile($this->getFilebaseFile($path), $this);
    $dest = new FilebaseDirectory($path->getPath(), $this);
    if(!$dest->fileExists()) throw new FilebaseException(sprintf('Destination directory %s does not exist.', $dest->getPathname()));
    if(!$dest->isDir()) throw new FilebaseException (sprintf('Destination %s is not a directory.',$dest->getPathname()));
    if(!$dest->isWritable()) throw new FilebaseException(sprintf('Destination directory %s is write protected.', $dest->getPathname()));
    if(!$this->isInFilebase($dest)) throw new FilebaseException(sprintf('Destination directory %s does not belong to filebase %s, access forbidden due to security issues.', $dest->getPathname(), $this->getPathname()));
    if($path->fileExists()) throw new FilebaseException (sprintf('File %s already exists',$path->getPathname()));
    if(!@touch($path->getPathname())) throw new FilebaseException(sprintf('Error creating file %s', $path->getPathname()));
    //  Chmodde file
    $path->chmod($perms);
    return $path;
  }

  /**
   * Checks if a file really belongs to this filebase.
   *
   * @param mixed FilebaseFile | string $file
   * @return boolean true if File belongs to this Filebase
   */
  public function isInFilebase($file_to_check)
  {
    $p = self::getFilebaseFile($file_to_check);
    while(true)
    {
      if($p->getPathname() == $this->getPathname())
        return true;

      //@todo check windows fs
      if($p->getPath() == '')
        return false;
      $p = new FilebaseDirectory($p->getPath(), $this->filebase); // parent dir
    }
  }

  /**
   * Returns the data describing
   * uploaded files to handle by
   * move uploaded files.
   *
   * @return array FilebaseUploadedFile $files
   */
  public function getUploadedFiles()
  {
    return $this->uploadedFilesManager->getUploadedFiles();
  }

  /**
   * Moves all uploaded files to the specified
   * destination directory.
   *
   * @see   UploadedFilesMananger::moveAllUploadedFiles()
   * @param mixed FilebaseDirectory | string $destination
   * @param boolean $override
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @return array FilebaseFile: The uploaded files
   */
  public function moveAllUploadedFiles($destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array())
  {
    return $this->uploadedFilesManager->moveAllUploadedFiles($destination_directory, $override, $chmod, $inclusion_rules, $exclusion_rules);
  }

  /**
   * Moves an uploaded File to specified Destination.
   * Inclusion and exclusion rules consist of regex-strings,
   * they are used to check the target filenames against them.
   * Exclusion:   Matched filenames throw exceptions.
   * Incluseion:  Matched filenames will be passed as valid filenames.
   *
   * $destination can be a string (absolute or relative pathname) or an
   * instance of FilebaseFile, it is the directory, not the full pathName of
   * the new file. The file can be renamed by setting $file_name, otherwise
   * the original name will be taken as filename.
   *
   * @param mixed $tmp_file
   * @param mixed $destination_directory: The directory the file will be moved in.
   * @param boolean $override True if existing files should be overwritten
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @param string $file_name: If given, file will be renamed when moving.
   * @throws FilebaseException
   * @return FilebaseFile $moved_file
   */
  public function moveUploadedFile(FilebaseUploadedFile $tmp_file, $destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array(), $file_name = null)
  {
    return $this->uploadedFilesManager->moveUploadedFile($tmp_file, $destination_directory, $override, $chmod, $inclusion_rules, $exclusion_rules, $file_name);
  }

  /**
   * Resizes an image, replacing the original version
   * by its resized avatar.
   *
   * @param mixed FilebaseImage | string $image
   * @param array $dimensions: array(width, height) or array('width'=>x ,'height'=>y)
   * @param integer $quality
   * @return FilebaseImage
   */
  public function resizeImage($image, array $dimensions, $quality = 60)
  {
    return $this->imageCopyResampled($image, $image, $dimensions, true);
  }

  /**
   * Resamples an image using php internal gd-functions. Used by thumbnail and resize
   * method.
   *
   * Dimensions is a 2-dim array with height, width or both, the other sides size is
   * calculated. It can have string-keys (width/height) or integer (0=>width,1=>height)
   *
   * @param mixed FilebaseImage | string $src: The souce image
   * @param mixed FilebaseImage | string $dst: The destination image, may be the same as $src
   * @param integer $width: The original width
   * @param integer $height: The original height
   * @param integer $dimensions: The new dimensions array('width'=>optional, 'height'=>optional, 0=>width, 1=>height)
   * @param boolean $overwrite: source image may be overwritten if set to true
   * @param integer $quality The sample-quality in percent
   */
  public function imageCopyResampled($src, $dst, array $dimensions, $overwrite = false, $quality = 60)
  {
    $this->gfxEditor->imageCopyResampled($src, $dst, $dimensions, $overwrite, $quality);
  }

  /**
   *
   * @param mixed FilebaseImage | string $image
   * @param array $dimensions
   * @param integer $quality
   * @return FilebaseImage $thumbnail
   */
  public function getThumbnailForImage($image, array $dimensions, $quality = 60, $mime='image/png')
  { 
    $image = $this->getFilebaseFile($image);
    $cache_fileinfo = $this->gfxEditor->getThumbnailFileinfo($image,$dimensions, $mime);
    if(!$cache_fileinfo->fileExists())
    {
      return $this->gfxEditor->createThumbnail($image, $dimensions, $quality, $mime);
    }
    return $cache_fileinfo;
  }

  /**
   * Rotates an image file
   *
   * @see GfxEditor::imageRotate()
   * @param FilebaseImage $image
   * @param float $deg
   * @param $bgcolor: The background color of the rotated image in html-hex
   *                  notation, default #00000
   * @return FilebaseImage $img: the rotated image
   */
  public function rotateImage (FilebaseImage $image, $deg, $bgcolor = '#000000')
  {
    return $this->gfxEditor->imageRotate($image, $deg, $bgcolor);
  }
}