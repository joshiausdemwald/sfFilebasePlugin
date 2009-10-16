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
 * sfFilebasePluginFile represents each file that is not
 * handled in a special way.
 *
 * @see        splFileInfo
 */
class sfFilebasePluginFile extends SplFileInfo
{
  /**
   * Reference to files' Filebase-instance
   *
   * @var sfFilebasePlugin $filebase
   */
  protected $filebase;
  
  /**
   * sfFilebasePluginFileObject reference,
   * only not null if file was
   * opened for reading/writing.
   * 
   * @var sfFilebasePluginFileObject
   */
  protected $filebaseFileObject = null;

  /**
   * Constructor
   *
   * @param string $file_name
   */
  public function __construct($path_name, sfFilebasePlugin $filebase)
  {
    $this->filebase = $filebase;
    
    // get rid of trainling slash
    parent::__construct(sfFilebasePluginUtil::unifySlashes($path_name));
    $this->setInfoClass('sfFilebasePluginFile');
    $this->setFileClass('sfFilebasePluginFileObject');
  }

  /**
   * Returns extension of file
   *
   * @return string $extension or null if file has no extension
   */
  public function getExtension()
  {
    $extension = pathinfo(parent::getFilename(),PATHINFO_EXTENSION);
    if(!empty($extension))
    {
      return $extension;
    }
    return null;
  }
  
  /**
   * Returns true if file exist in filesystem.
   *
   * @return boolean
   */
  public function fileExists()
  {
    return $this->filebase->getFileExists($this);
  }

  /**
   * Returns true if a file is hidden or null
   * if that cannot be determined (e.g. unter
   * windows).
   *
   * @todo check functionlity under windows ntfs
   * @return boolean $is_hidden: true if file is hidden
   */
  public function isHidden()
  {
    if(!$this->fileExists()) throw new sfFilebasePluginException(sprintf('File %s does not exist.', $this->getPathname()));
    if(!$this->isReadable()) throw new sfFilebasePluginException(sprintf('File %s is not readable.', $this->getPathname()));

    $is_hidden = false;

    // *nix: Simply check the dot.
    if(strlen($this->getFilename()) > 1 && strpos($this->getFilename(), '.') === 0)
    {
      $is_hidden = true;
    }

    // Windoof...this is taken from php.net
    $attr = trim(@exec('FOR %A IN ("'.$this->getPathname().'") DO @ECHO %~aA'));
    if(isset($attr[3]))
    {
      $attr[3] === 'h' && $is_hidden = true;
    }
    return $is_hidden;
  }

  /**
   * Returns true if file is a link
   */
  public function isLink()
  {
    return is_link($this->getPathname());
  }
  
  /**
   * Returns true if sfFilebasePluginFile is an
   * image file. Used to factory
   * a sfFilebasePluginImage instance by sfFilebasePlugin::
   * getFilebaseFile()
   *
   * @see sfFilebaseUtil::getIsImage()
   * @todo improved mime-type detection
   * @return boolean true if file is an image
   */
  public function isImage()
  {
    return $this->filebase->getIsImage($this);
  }

  /**
   * Returns true if sfFilebasePluginFile is a
   * <strong>web</strong> image file. Used to factory
   * a sfFilebasePluginImage instance by sfFilebasePlugin::
   * getFilebaseFile()
   *
   * @see sfFilebasePlugin::getIsWebImage()
   * @todo improved mime-type detection
   * @return boolean true if file is an image
   */
  public function isWebImage()
  {
    return $this->filebase->getIsWebImage($this);
  }

  /**
   * ToString method
   *
   * @return string $pathname
   */
  public function __toString()
  {
    return $this->getPathname();
  }
  
  /**
   * If Format is string, Size will be formatted (kb, mb ...) 
   *
   * @throws sfFilebasePluginException
   * @param string $format = null | string
   */
  public function getSize($format=null)
  {
    if(!$this->fileExists())  throw new sfFilebasePluginException(sprintf('File %s does not exist.', $this->getPathname()));
    if(!$this->isReadable())  throw new sfFilebasePluginException(sprintf('File %s is not readable.', $this->getPathname()));
    $size = parent::getSize();
    if($format)
    {
      return sfFilebasePluginUtil::getStringFilesize((double)$size);
    }
    return $size;
  }
  
  public function getCTime($format = false)
  {
    if($format)
      return date($format, parent::getCTime());
    return parent::getCTime();
  }
  
  public function getMTime($format = false)
  {
    if($format)
      return date($format, parent::getMTime());
    return parent::getMTime();
  }
  
  public function getATime($format = false)
  {
    if($format)
      return date($format, parent::getATime());
    return parent::getATime();
  }
  
  /**
   * Retrieves Content-Type (mime-type)
   * of File.
   * 
   * @return string
   */
  public function getContentType()
  {
    return $this->getMimeType();
  }

  /**
   * Returns Mime-Type (Content-Type)
   * of file or default if mime type
   * cannot be determined.
   *
   * @param  string $default;
   * @return string $mime
   */
  public function getMimeType($default = 'application/octet-stream')
  {
    return sfFilebasePluginUtil::getMimeType($this, $default);
  }

  /**
   * Returns a sfFilebasePlugin instance
   *
   * @return sfFilebasePlugin $filebase
   */
  public function getFilebase()
  {
    return $this->filebase;
  }

  /**
   * Returns true if file is read- and write-able.
   * 
   * @return boolean
   */
  public function isRWritable()
  {
    return parent::isReadable() && parent::isWritable();
  }

  /**
   * Returns true if file exists and is writable.
   *
   * @return boolean
   */
  public function existsAndIsWritable()
  {
    return self::fileExists() && parent::isWritable();
  }

  /**
   * Returns an unique hash value representation
   * of the given pathname, relative from filebaseDirectory.
   *
   * If $absolute is set to true, the hash will be calculated
   * from the file's absolute pathname. Note that this will prevent
   * you from finding a file by it's hash value after your filesystems
   * directory layout has been changed.
   *
   * @see   sfFilebasePlugin::getHashForFile
   * @param boolean $absolute
   * @return string $hash_value
   */
  public function getHash($absolute = false)
  {
    return $this->getFilebase()->getHashForFile($this, $absolute);
  }

  /**
   * Trys to delete a file from fs.
   *
   * @see sfFilebasePlugin::delete()
   * @throws sfFilebasePluginException
   */
  public function delete()
  {
    return $this->filebase->deleteFile($this);
  }

  /**
   * Changes the access permissions of a sfFilebasePluginFile
   *
   * @param integer
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $file
   */
  public function chmod($perms = 0755)
  {
    return $this->filebase->chmodFile($this, $perms);
  }

  /**
   * Renames a file.
   *
   * @param string $new_name: The new filename
   * @param boolean $overwrite: If true, existing files with same name will be
   *                            overwritten.
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $file
   */
  public function rename($new_name, $overwrite = false)
  {
    return $this->filebase->renameFile($this, $new_name, $overwrite);
  }

  /**
   * Copies a file to the given destination.
   *
   * @param mixed sfFilebasePluginFile | string $destination
   * @param boolean $overwrite: If true dest file will be overwritten
   * @return sfFilebasePluginFile $copied_file
   */
  public function copy($destination, $overwrite=false)
  {
    return $this->filebase->copyFile($this, $destination, $overwrite);
  }


  /**
   * Moves a file to the given destination file or directory.
   *
   * @param mixed sfFilebasePluginFile | string destination
   * @param boolean $allow_overwrite: Move and replace existing files
   * @return sfFilebasePluginFile $moved_file
   */
  public function move($destination, $allow_overwrite = false)
  {
    return $this->filebase->moveFile($this, $destination, $allow_overwrite);
  }

  /**
   * Converts an absolute Pathname to a relative one,
   * as seen from sfFilebasePlugin-Directory.
   *
   * @throws sfFilebasePluginException
   * @return string $relative_pathname
   */
  public function getRelativePathFromFilebaseDirectory()
  {
    if(!$this->getFilebase()->isInFilebase($this)) throw new sfFilebasePluginException(sprintf('Path %s does not lie within FilebaseDirectory', $this->getPathname()));
    return ltrim(preg_replace('#^'.sfFilebasePluginUtil::unifySlashes($this->getFilebase()->getPathname()).'#','', sfFilebasePluginUtil::unifySlashes($this->getPathname()),1),'/\\');
  }

   /**
   * Returns filename, as seen absolutely from webroot.
   *
   * @throws sfFilebasePluginException
   * @return string $path
   */
  public function getAbsolutePathFromWebroot()
  {
    if(!$this->getFilebase()->isInFilebase($this)) throw new sfFilebasePluginException(sprintf('Path %s does not lie within FilebaseDirectory', $this->getPathname()));
    $path = '/' . ltrim(preg_replace('#^'.sfFilebasePluginUtil::unifySlashes($_SERVER['DOCUMENT_ROOT']).'#i','', sfFilebasePluginUtil::unifySlashes($this->getPathname())), '/\\');
    return $path;
  }

  /**
   * Returns a new (child of) sfFilebasePluginFile. It returns an instance,
   * not regarding if the file exists or not. So you can fetch an instance
   * and create the underlying file by sfFilebasePlugin-methods.
   *
   * This method checks if file is a directory, an image
   * a link ... but beware, some attributes can only
   * be calculated if the file really exists in file system.
   *
   * For example, if you want to retrieve a directory that does
   * not exists, an instance of sfFilebasePluginFile is recieved.
   *
   * This behavior is simple and logically correct, but you have to keep it in
   * mind: sfFilebasePlugin cannot forecast what files you want to create. It
   * is probably more confusing for windows users where files are mostly
   * identified by its extension.
   *
   * If the dir exists, you'll get a sfFilebasePluginDirectory.
   *
   * @example $filebase->getFilebaseFile('path/to/directory') retrieves a dir
   *          if it exists in FS, or a sfFilebaseFile if not.
   *
   * @example Creating a new file:
   *          $new_file = $filebase->getFilebaseFile('path/to/new/file.txt');
   *          $filebase->touch($new_file);
   *          In Short: $filebase->touch('path/to/new/file.txt');
   *
   * @example Short way retrieving files:
   *          foreach ($filebase AS $file) ...
   *          with ArrayAccess:
   *          $file = $filebase['/path/to/file'];
   *
   * @todo Implement (sym)link handling.
   * @param string | sfFilebasePluginFile  $filename
   * @return sfFilebasePluginFile
   */
  public function getFilebaseFile($filename)
  {
    if(is_string($filename))
    {
      //$filename = sfFilebasePluginUtil::unifySlashes($filename);
      if(strlen($filename)>0)
      {
        if(sfFilebasePluginUtil::isAbsolutePathname($filename))
        {
          $filename = new sfFilebasePluginFile($filename, $this->filebase);
        }
        else
        {
          $filename = new sfFilebasePluginFile($this->getPathname().'/'.$filename, $this->filebase);
        }
      }
      else
      {
        $filename = $this;
      }
    }
    if($filename instanceof sfFilebasePluginFile)
    {
      // returns only true if file exists, so beware of that
      if($filename->isLink())
      {
        throw new sfFilebasePluginException(sprintf('Error retrieving link %s: Link handling is not yet implemented', $filename->getPathname()));
        //return new sfFilebasePluginLink($filename->getPathname());
      }
      // returns only true if file exists, so beware of that
      elseif($filename->isDir())
      {
        $filename = new sfFilebasePluginDirectory($filename->getPathname(), $this->filebase);
      }
      
      // Can image be processed by filebase, either throw GD or throug imagick?
      // Then return instance of sfFilebasePluginImage...
      elseif($this->filebase->getIsSupportedImage($filename))
      {
        $filename = new sfFilebasePluginImage($filename->getPathname(), $this->filebase);
      }
      return $filename;
    }
    throw new sfFilebasePluginException(sprintf('File %s must be of type [string] or instanceof FilebaseFile', $filename));
  }

  /**
   * Opens the file for read/writing
   * 
   * @param string $open_mode
   * @param ressource $context stream context
   * @return sfFilebasePluginFileObject $file
   */
  public function openFile ($open_mode = 'r', $use_include_path = false, $context = null)
  {
    if(!$this->fileExists()) throw new sfFilebasePluginException (sprintf('File %s does not exist.', $this->getPathname()));
    $this->filebaseFileObject = new sfFilebasePluginFileObject($this, $this->getFilebase(), $open_mode, false, $context);
    return $this->filebaseFileObject;
  }

  /**
   * Returns a human readable string file type.
   * This could be for example "jpeg image" or "wma audio"
   * 
   * @return string
   */
  public function getHumanReadableFileType()
  {
    return sfFilebasePluginUtil::getStringTypeByExtension($this->getExtension());
  }

  /**
   * Returns the nesting level of the file, relative to its base filebase
   * directory.
   *
   * @return integer $nesting_level
   */
  public function getNestingLevel()
  {
    $rel_path = $this->getRelativePathFromFilebaseDirectory();
    return count(split('/', $rel_path));
  }

  /**
   * Checks if this file is a child of the given filebase
   * directory.
   *
   * @see sfFilebasePlugin::fileLiesWithin
   * @param mixed sfFilebaseDirectory | string $file
   * @return boolean
   */
  public function liesWithin($file)
  {
    return $this->filebase->fileLiesWithin($this, $file);
  }
}