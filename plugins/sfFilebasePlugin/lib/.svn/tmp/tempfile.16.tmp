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
 * sfFilebasePlugin provides easy access to file system in oop style
 *
 * @see        SplFileInfo
 */
class sfFilebasePlugin extends sfFilebasePluginDirectory
{
  /**
   * FileInfo Object of Cache Directory
   *
   * @var sfFilebasePluginFile $cacheDirectory
   */
  protected $cacheDirectory;

  /**
   * @var sfFilebasePluginUploadedFilesManager $uploadedFilesManager
   */
  protected $uploadedFilesManager;
  
  /**
   * @var sfFilebasePluginGfxEditor
   */
  protected $gfxEditor;

  /**
   * Holds cached instances of filebase for retrieving
   * them by a static factory method.
   *
   
   * @see sfFilebasePlugin::getInstance()
   * @staticvar array sfFilebasePlugin $sf_filebase_plugins
   */
  static protected $instances = array();

  /**
   * Constructor. Parameters are filebase-"root"-directory and cache-dir for
   * e.g. temp-files and thumbnails.
   *
   * You can specify $path_name and $cache_directory by yourself, but you don't
   * have to. In symfony context, the $path_name is
   *   app.yml:
   *     sf_filebase_plugin:
   *       path_name: /your/path/to/filebase
   *       cache_directory: /your/path/to/.cache
   *
   * or, if not defined, calculated from the predefined sfConfig-variables:
   *   $path_name       = sfConfig::get('sf_upload_dir') => web/uploads
   *   $cache_directory = sfConfig::get('sf_upload_dir') . '/.hash_from_clasname'
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
   * @param   boolean $create             True if the filebase should try to
   *                                      create its base directory if it does
   *                                      not exist.
   * @throws  sfFilebasePluginException
   * @param   string $mode
   */
  function __construct($path_name = null, $cache_directory = null, $create = true, $dir_chmod = 0777)
  {
    if($path_name === null)
    {
      $path_name = sfConfig::get('app_sf_filebase_plugin_path_name', sfConfig::get('sf_upload_dir'));
    }
    if($cache_directory === null)
    {
      $cache_directory = sfConfig::get('app_sf_filebase_plugin_cache_directory', $path_name . '/.' . md5(get_class()));
    }

    $path_name === null && $path_name = sfConfig::get('sf_upload_dir', null);

    parent::__construct($path_name, $this);

    if(!$this->fileExists())
    {
      // Should filebase dir be created by default?
      if($create)
      {
        $p = $this->getFilebaseFile($this->getPath());
        if($p->isWritable())
        {
          if(!@mkdir($this->getPathname())) throw new sfFilebasePluginException(sprintf('Error creating filebase base directory %s: %', $this->getPathname(), implode("\n", error_get_last())));
          if(!@chmod($this->getPathname(), $dir_chmod)) throw new sfFilebasePluginException(sprintf('Error changing directory permissions for filebase base directory %s: %', $this->getPathname(), implode("\n", error_get_last())));
        }
        else throw new sfFilebasePluginException(sprintf('Filebase base directory %s cannot be created due to write protection of its parent directory.', $this->getPathname()));
      }
      else throw new sfFilebasePluginException(sprintf('Filebase base directory %s does not exist.', $this->getPathname()));
    }
    if(!$this->isDir())         throw new sfFilebasePluginException(sprintf('Filebase base directory %s is not a directory', $this->getPathname()));
    if(!$this->isReadable())    throw new sfFilebasePluginException(sprintf('Filebase base directory %s is not readable', $this->getPathname()));
    // Filebase root must have read/write-access.
    if(!$this->isWritable())   throw new sfFilebasePluginException(sprintf('Filebase base directory %s is read or write protected', $this->getPathname()));
    
    // Initialize cache.
    $this->initCache($cache_directory);
    $this->uploadedFilesManager = new sfFilebasePluginUploadedFilesManager($this);
    $this->gfxEditor            = new sfFilebasePluginGfxEditor($this);
  }

  /**
   * Static factory for filebases, can be used to access
   * filebase from everywhere without creating more than
   * one instance with the same properties.
   * 
   * @param string $path_name
   * @param string $cache_directory
   * @return sfFilebasePlugin $filebase
   */
  public static function getInstance($path_name = null, $cache_directory = null, $create = true)
  {
    $hashcookie = md5($path_name.$cache_directory);
    if(!array_key_exists($hashcookie, self::$instances))
    {
      self::$instances[$hashcookie] = new sfFilebasePlugin($path_name, $cache_directory);
    }
    return self::$instances[$hashcookie];
  }

  /**
   * Checks if a file exists in file system.
   *
   * @param boolean true if $filename exists filebase
   */
  public function getFileExists($filename)
  {
    return file_exists($this->getFilebaseFile($filename));
  }

  /**
   * Checks if CacheDirectory exists, if
   * not, then create it.
   *
   * @param $cache_directory Path to cache_dir.
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $cache_directory
   */
  protected function initCache($cache_directory)
  {
    $this->cacheDirectory = $this->getFilebaseFile($cache_directory);

    if(!$this->cacheDirectory->fileExists())
    {
      self::mkDir($this->cacheDirectory, 0777);
    }
    return $this->cacheDirectory;
  }

  /**
   * Returns cache directory for this filebase.
   * 
   * @return sfFilebasePluginDirectory $cacheDirectory
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
   * If $absolute is set to true, the hash will be calculated
   * from the file's absolute pathname. Note that this will prevent
   * you from finding a file by it's hash value.
   *
   * @todo implement finding file by it's absolute pathname's hash
   * @param mixed sfFilebasePluginFile | string $file
   * @param boolean $absolute
   * @return string $hash_value
   */
  public function getHashForFile($file, $absolute = false)
  {
    $file = $this->getFilebaseFile($file);
      return sha1($absolute ? $file->getPathname() : $file->getRelativePathFromFilebaseDirectory());
  }

  /**
   * Moves a file to the given destination directory or pathname.
   * 
   * @param mixed  sfFilebasePluginFile | string $source
   * @param mixed  sfFilebasePluginFile | string $destination
   * @param boolean $allow_overwrite
   * @returns sfFilebasePluginFile The moved file.
   */
  public function moveFile($source, $destination, $allow_overwrite = true)
  {
    $source       = $this->getFilebaseFile($source);
    $destination  = $this->getFilebaseFile($destination);
    
    if(!$source->fileExists()) throw new sfFilebasePluginException(sprintf('Source file %s does not exist.', $source));
    if(!$source->isReadable()) throw new sfFilebasePluginException(sprintf('Source file %s is read protected.', $source));

    if(!$this->isInFilebase($destination))  throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname()));
    if(!$this->isInFilebase($source))       throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $source->getPathname(), $this->getPathname()));

    if($destination->fileExists())
    {
      if($destination instanceof sfFilebasePluginDirectory)
      {
        $destination = $destination . '/' . $source->getFilename();
        return $this->moveFile($source, $destination);
      }
      else
      {
        if($allow_overwrite)
        {
          $destination->delete();
          
        } else throw new sfFilebasePluginException(sprintf('Destination file %s already exists.', $destination));
      }
    }
    else
    {
      $parent = $this[$destination->getPath()];
      if(!$parent->isWritable()) throw new sfFilebasePluginException(sprintf('Destination directory %s is write protected.', $parent));
    }
    if(!@rename($source->getPathname(), $destination->getPathname())) throw new sfFilebasePluginException(sprintf('Error renaming %s to %s.', $source, $destination));
    return $destination->isDir() ? new sfFilebasePluginDirectory($destination, $this) : $destination;
  }

  /**
   * Copies a whole directory to a destination directory.
   * The name of the new directory is the name of the copied source
   * directory. If allow_overwrite isset, existing files and directories
   * containing files with names of copied files will be overwritten.
   *
   * @param mixed sfFilebasePluginDirectory | string $source
   * @param mixed sfFilebasePluginDirectory | string $destination
   * @param boolean $allow_overwrite: True if destination contents may be
   *                                  overwritten.
   * @return sfFilebaseDirectory $directory: The copied $directory.
   */
  public function copyDirectory($source, $destination, $allow_overwrite = true)
  {
    $source                 = $this->getFilebaseFile($source);
    $destination            = $this->getFilebaseFile($destination);
    
    if(!$source->fileExists())                        throw new sfFilebasePluginException(sprintf('Error copying file %s: File does not exist.', $source->getPathname()));
    if(!$source->isReadable())                        throw new sfFilebasePluginException(sprintf('Error copying file %s: Source is read protected.', $source->getPathname()));
    if(!$source instanceof sfFilebasePluginDirectory) throw new sfFilebasePluginException(sprintf('Source file %s is not a directory.', $source->getPathname()));
    if(!$this->isInFilebase($destination))   throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname()));
    if($destination->liesWithin($source))  throw new sfFilebasePluginException(sprintf('Error copying file: Destination directory %s lies within source directory %s.', $destination, $source));

    // Destination directory does not exist, so createit.
    // Example: copy /hans/wurst to /otherdir/newdir/newname
    // With this man can change the target directory filename
    if($destination->fileExists())
    {
      if($destination instanceof sfFilebaseDirectory)
      {
        $destination = $destination . '/' . $source->getFilename();
        return $this->copyDirectory($source, $destination);
      }
      else
      {
        if($allow_overwrite)
        {
          $destination->delete();
        }
        else throw new sfFilebasePluginException(sprintf('Destination %s already exists.', $destination));
      }
    }
    else
    {
      $destination = $this->mkDir($destination, $source->getPerms());
    }

    // @ todo check this behaviour
    foreach($source AS $file)
    {
      $file->copy($destination . '/' .  $file->getPathname());
    }
    return $destination;
  }

  /**
   * Copies a file to the given destination.
   *
   * @param mixed sfFilebasePluginFile | string $source: The source file
   * @param mixed sfFilebasePluginFile | string $destination: The destination
   *              directory
   * @param boolean $allow_overwrite: If true dest file will be overwritten
   * @return sfFilebasePluginFile $copied_file
   */
  public function copyFile($source, $destination, $allow_overwrite = true)
  {
    $source                 = $this->getFilebaseFile($source);
    $destination            = $this->getFilebaseFile($destination);
    
    if(!$source->fileExists()) throw new sfFilebasePluginException(sprintf('Error copying file %s: File does not exist.', $source->getPathname()));
    if(!$source->isReadable()) throw new sfFilebasePluginException(sprintf('Error copying file %s: Source is read protected.', $source->getPathname()));
    
    // Only check target. Files may be copied from any location.
    if(!$this->isInFilebase($destination))                            throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname()));

    if($source instanceof sfFilebasePluginDirectory)
    {
      $this->copyDirectory($source, $destination_directory, $allow_overwrite);
    }
    else
    {
      // if destination exists, check:
      // it is a directory: move file within dir
      // it is a file: try to overwrite it.
      if($destination->fileExists())
      {
        if($destination instanceof sfFilebasePluginDirectory)
        {
          $destination = $destination . '/' . $source->getFilename();
          return $this->copyFile($source, $destination, $allow_overwrite);
        }
        else
        {
          if($allow_overwrite)
          {
            $destination->delete();
          }
          else throw new sfFilebasePluginException(sprintf('File %s already exists.', $destination));
        }
      }
      else
      {
        $parent = $this[$destination->getPath()];
        if(!$parent->isWritable()) throw new sfFilebasePluginException(sprintf('Directory %s is write protected', $parent));
      }
      
      if(!@copy($source->getPathname(), $destination->getPathname()))   throw new sfFilebasePluginException(sprintf('Error copying file %s to %s.', $source->getPathname(), $destination->getPathname()));
    }
    return $destination;
  }

  /**
   * Renames a file.
   *
   * @see sfFilebasePlugin::moveFile()
   * @param mixed sfFilebasePluginFile | string $source:      The source file-name
   * @param mixed sfFilebasePluginFile | string $destination: The target file-name
   *              Target can be only a filename, than the path of the source file
   *              name will be prepended. If it is a full path name or an instance of
   *              sfFilebasePluginFile, the destinantions path will be ignored and also
   *              the source paths filename will be prepended.
   * @param boolean $override: true if existing destination shall be overwritten.
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $file
   */
  public function renameFile($source, $new_name, $overwrite = false)
  {
    $new_name = $this->getFilebaseFile($new_name);
    return $this->moveFile($source, $source->getPath() . '/' . $new_name->getFilename(), $overwrite);
  }

  /**
   * Trys to delete a file from fs. If file is a directory, deleteDirectory is
   * called.
   *
   * @see sfFilebasePlugin::deleteDirectory()
   * @param boolean $allow_recursive true if isDir() && contents may be deleted true
   * @param sfFilebasePluginFile | string $file
   * @throws sfFilebasePluginException
   */
  public function deleteFile($file, $allow_recursive = false)
  {
    $file = $this->getFilebaseFile($file);
    if(!$file->fileExists())        throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not exist.', $file->getPathname()));
    if(!$this->isInFilebase($file)) throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $file->getPathname(), $this->getPathname()));
    
    if($file instanceof sfFilebasePluginDirectory)
    {
      return $this->deleteDirectory($file, $allow_recursive);
    }

    if(!$file->isWritable())        throw new sfFilebasePluginException(sprintf('File %s is write protected.', $file->getPathname()));

    if(!@unlink($file->getPathname()))
    {
      throw new sfFilebasePluginException(sprintf('Error while deleting file %s: %s', $file->getPathname(), implode("\n", error_get_last())));
    }
    return true;
  }

  /**
   * Trys to delete the directory from fs.
   *
   * @param mixed sfFilebasePluginFile | string $dir
   * @param boolean $allow_recursive true if contents may be deleted true
   * @throws sfFilebasePluginException
   * @return boolean true if deletion was successful
   */
  public function deleteDirectory ($directory, $allow_recursive = false)
  {
    $directory = $this->getFilebaseFile($directory);
    if(!$this->isInFilebase($directory))                    throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $directory->getPathname(), $this->getPathname()));
    if(!$directory->fileExists())                           throw new sfFilebasePluginException(sprintf('Directory %s does not exist.', $directory->getPathname()));
    if(!$directory instanceof sfFilebasePluginDirectory)    throw new sfFilebasePluginException(sprintf('File %s is not a directory.', $directory->getPathname()));
    if(!$directory->isWritable())                           throw new sfFilebasePluginException(sprintf('Directory %s is write protected.', $directory->getPathname()));
    
    if($allow_recursive)
    {
      foreach($directory AS $file)
      {
        $this->deleteFile($file, true);
      }
    }
    
    if(!$directory->isEmpty())                              throw new sfFilebasePluginException(sprintf('Error deleting directory %s: Directory is not empty.', $directory->getPathname()));

    if(!@rmdir($directory->getPathname()))
    {
      throw new sfFilebasePluginException(sprintf('Error while deleting directory %s: %s', $directory->getPathname(), error_get_last()));
    }
    return true;
  }

  /**
   * Changes the access permissions of a sfFilebasePluginFile
   *
   * @param sfFilebasePluginFile | string: The file to chmod()
   * @param integer
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $file
   */
  public function chmodFile($destination, $perms = 0755)
  {
    if(!$destination->fileExists())         throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not exist.', $destination->getPathname()));
    if(!$this->isInFilebase($destination))  throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to filebase %s, access denied due to security issues.', $destination->getPathname(), $this->getPathname()));
    if(!$destination->isWritable())                         throw new sfFilebasePluginException(sprintf('FilebaseFile %s is write protected.', $destination->getPathname()));
    if(!@chmod($destination->getPathname(), $perms))        throw new sfFilebasePluginException(sprintf('Error chmod-ing directory %s: %s', $destination->getPathname(), implode("\n", error_get_last())));
    return $destination;
  }

  /**
   * Creates a new directory. Throws exceptions if target is not
   * writable, dir already exists etc...
   *
   * @param mixed sfFilebasePluginFile | string $path
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $file
   */
  public function mkDir($path, $perms = 0755)
  {
    // Wrap around, because isDir() returs false on non-existing files.
    $path = new sfFilebasePluginDirectory($this->getFilebaseFile($path), $this);
    $dest = new sfFilebasePluginDirectory($path->getPath(), $this);
    if(!$dest->fileExists()) throw new sfFilebasePluginException(sprintf('Destination directory %s does not exist.', $dest->getPathname()));
    if(!$dest->isDir()) throw new sfFilebasePluginException (sprintf('Destination %s is not a directory.',$dest->getPathname()));
    if(!$dest->isWritable()) throw new sfFilebasePluginException(sprintf('Destination directory %s is write protected.', $dest->getPathname()));
    if(!$this->isInFilebase($dest)) throw new sfFilebasePluginException(sprintf('Destination directory %s does not belong to filebase %s, access forbidden due to security issues.', $dest->getPathname(), $this->getPathname()));
    if($path->fileExists()) throw new sfFilebasePluginException (sprintf('Directory %s already exists',$path->getPathname()));
    if(!@mkdir($path->getPathname())) throw new sfFilebasePluginException(sprintf('Error creating directory %s', $path->getPathname()), 2010);
    //  Chmodde dir
    $path->chmod($perms);
    return $path;
  }

  /**
   * Creates a new empty file using touch().
   *
   * @param mixed sfFilebasePluginFile | string $path
   * @param integer $perms
   * @return sfFilebasePluginFile $new_file
   */
  public function touch($path, $perms = 0755)
  {
     // Wrap around, because isDir() returs false on non-existing files.
    $path = new sfFilebasePluginFile($this->getFilebaseFile($path), $this);
    $dest = new sfFilebasePluginDirectory($path->getPath(), $this);
    if(!$dest->fileExists()) throw new sfFilebasePluginException(sprintf('Destination directory %s does not exist.', $dest->getPathname()));
    if(!$dest->isDir()) throw new sfFilebasePluginException (sprintf('Destination %s is not a directory.',$dest->getPathname()));
    if(!$dest->isWritable()) throw new sfFilebasePluginException(sprintf('Destination directory %s is write protected.', $dest->getPathname()));
    if(!$this->isInFilebase($dest)) throw new sfFilebasePluginException(sprintf('Destination directory %s does not belong to filebase %s, access forbidden due to security issues.', $dest->getPathname(), $this->getPathname()));
    if($path->fileExists()) throw new sfFilebasePluginException (sprintf('File %s already exists',$path->getPathname()));
    if(!@touch($path->getPathname())) throw new sfFilebasePluginException(sprintf('Error creating file %s', $path->getPathname()));
    //  Chmodde file
    $path->chmod($perms);
    return $path;
  }

  /**
   * Checks if a file really belongs to this filebase.
   *
   * @param mixed sfFilebasePluginFile | string $file
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
      $p = new sfFilebasePluginDirectory($p->getPath(), $this->filebase); // parent dir
    }
  }
  
  /**
   * Returns the upload file manager for this
   * instance of filebase
   *
   * @return sfFilebasePluginUploadedFilesManager
   */
  public function getUploadedFilesManager()
  {
    return $this->uploadedFilesManager;
  }

  /**
   * Returns the data describing
   * uploaded files to handle by
   * move uploaded files.
   *
   * @throws FilebaseException
   * @return array sfFilebasePluginUploadedFile $files
   */
  public function getUploadedFiles($index = null)
  {
    $files = $this->uploadedFilesManager->getUploadedFiles();
    if($index === null)
    {
      return $files;
    }
    if(is_array($files) && array_key_exists($index, $files))
    {
      return $files[$index];
    }
    throw new sfFilebasePluginException(sprintf('Uploaded file %s does not exist.', $index));
  }

  /**
   * Moves all uploaded files to the specified
   * destination directory.
   *
   * @see   UploadedFilesMananger::moveAllUploadedFiles()
   * @param mixed sfFilebasePluginDirectory | string $destination
   * @param boolean $override
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @return array sfFilebasePluginFile: The uploaded files
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
   * instance of sfFilebasePluginFile, it is the directory, not the full pathName of
   * the new file. The file can be renamed by setting $file_name, otherwise
   * the original name will be taken as filename.
   *
   * @param mixed $tmp_file
   * @param mixed $destination_directory: The directory the file will be moved in.
   * @param boolean $override True if existing files should be overwritten
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @param string $file_name: If given, file will be renamed when moving.
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $moved_file
   */
  public function moveUploadedFile(sfFilebasePluginUploadedFile $tmp_file, $destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array(), $file_name = null)
  {
    return $this->uploadedFilesManager->moveUploadedFile($tmp_file, $destination_directory, $override, $chmod, $inclusion_rules, $exclusion_rules, $file_name);
  }

  /**
   * Resizes an image, replacing the original version
   * by its resized avatar.
   *
   * @param mixed sfFilebasePluginImage | string $image
   * @param array $dimensions: array(width, height) or array('width'=>x ,'height'=>y)
   * @param integer $quality
   * @param boolean $preserve_transparency: True if transparency should be
   *                                        preserved
   * @return sfFilebasePluginImage
   */
  public function resizeImage($image, array $dimensions, $quality = 60, $preserve_transparency = true)
  {
    return $this->imageCopyResampled($image, $image, $dimensions, true, $quality, $preserve_transparency);
  }

  /**
   * Resamples an image using php internal gd-functions. Used by thumbnail and resize
   * method.
   *
   * Dimensions is a 2-dim array with height, width or both, the other sides size is
   * calculated. It can have string-keys (width/height) or integer (0=>width,1=>height)
   *
   * @param mixed sfFilebasePluginImage | string $src: The souce image
   * @param mixed sfFilebasePluginImage | string $dst: The destination image, may be the same as $src
   * @param integer $width: The original width
   * @param integer $height: The original height
   * @param integer $dimensions: The new dimensions array('width'=>optional, 'height'=>optional, 0=>width, 1=>height)
   * @param boolean $overwrite: source image may be overwritten if set to true
   * @param integer $quality The sample-quality in percent
   * @param integer $preserve_transparency: If set to true, transparency of
   *                                        png or gif images shall be preserved.
   * 
   */
  public function imageCopyResampled($src, $dst, array $dimensions, $overwrite = false, $quality = 60, $preserve_transparency = true)
  {
    return $this->gfxEditor->imageCopyResampled($src, $dst, $dimensions, $overwrite, $quality, $preserve_transparency);
  }

  /**
   *
   * @param mixed sfFilebasePluginImage | string $image
   * @param array $dimensions
   * @param integer $quality
   * @param integer $preserve_transparency: If set to true, transparency of
   *                                        png or gif images shall be preserved.
   * @return sfFilebasePluginImage $thumbnail
   */
  public function getThumbnailForImage($image, array $dimensions, $quality = 60, $mime='image/png', $preserve_transparency = true)
  { 
    $image = $this->getFilebaseFile($image);
    $cache_fileinfo = $this->gfxEditor->getThumbnailFileinfo($image,$dimensions, $mime);

    if(!$cache_fileinfo->fileExists())
    {
      return $this->gfxEditor->createThumbnail($image, $dimensions, $quality, $mime, $preserve_transparency);
    }
    return $cache_fileinfo;
  }

  /**
   * Rotates an image file
   *
   * @see sfFilebasePluginGfxEditor::imageRotate()
   * @param sfFilebasePluginImage $image
   * @param float $deg
   * @param $bgcolor: The background color of the rotated image in html-hex
   *                  notation, default #00000
   * @param integer $preserve_transparency: If set to true, transparency of
   *                png or gif images shall be preserved.
   * @return sfFilebasePluginImage $img: the rotated image
   */
  public function rotateImage (sfFilebasePluginImage $image, $deg, $bgcolor = '#000000', $preserve_transparency = true)
  {
    return $this->gfxEditor->imageRotate($image, $deg, $bgcolor, $preserve_transparency);
  }

   /**
   * Returns true if sfFilebasePluginFile is an
   * image file. Used to factory
   * a sfFilebasePluginImage instance by sfFilebasePlugin::
   * getFilebaseFile()
   *
   * @see sfFilebasePluginUtil::getIsImage()
   * @todo improved mime-type detection
   * @return boolean true if file is an image
   */
  public function getIsImage(sfFilebasePluginFile $file)
  {
    return sfFilebasePluginUtil::getIsImage($file);
  }

  /**
   * Returns true if $file is a supported image, what means that it can
   * be processed by either GD-lib or imagick extension
   *
   * @param sfFilebasePluginFile $file
   * @return boolean $is_supported_image: True if $file is supported
   */
  public function getIsSupportedImage(sfFilebasePluginFile $file)
  {
    return sfFilebasePluginUtil::getIsSupportedImage($file);
  }

  /**
   * Returns true if sfFilebasePluginFile is an
   * <strong>web</strong> image file. Used to factory
   * a sfFilebasePluginImage instance by sfFilebasePlugin::
   * getFilebaseFile()
   *
   * @see sfFilebasePluginUtil::getIsWebImage()
   * @param sfFilebaseUtilFile $file
   * @return boolean true if file is an image.
   */
  public function getIsWebImage(sfFilebasePluginFile $file)
  {
    return sfFilebasePluginUtil::getIsWebImage($file);
  }

  /**
   * Returns a file that filename previously was
   * encoded by getHash().
   *
   * Returns null if file does not exist.
   *
   * @todo retrieve file by absolute pathname's hash
   * @param string Hash representation of relativePathFromFilebaseDirectory
   * @return sfFilebasePluginFile $file
   */
  public function getFileByHash($hash)
  {
    if($hash === $this->getHash())
      return $this;
    foreach(new RecursiveIteratorIterator($this->getIterator(), RecursiveIteratorIterator::SELF_FIRST) AS $file)
    {
      if($file->getHash() === $hash)
      {
        return $file;
      }
    }
    return null;
  }

  /**
   * Checks if this file is a child of the given filebase
   * directory.
   * @param mixed sfFilebaseDirectory | string $file
   */
  public function fileLiesWithin($file, $container)
  {
    $file       = $this->getFilebaseFile($file);
    $container  = $this->getFilebaseFile($container);
    // file = /path/to/lalala/bumsdi
    // container = /path/to
    
    if(strpos($file->getPathname(), $container->getPathname()) === 0)
    {
      return true;
    }
    return false;
  }
}