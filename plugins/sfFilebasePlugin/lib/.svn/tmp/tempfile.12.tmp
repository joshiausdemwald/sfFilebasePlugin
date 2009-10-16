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
 * UploadedFilesManager holds instances of FilebaseUploadedFiles.
 * It provide methods to access these objects and moves their referenced
 * temp-files to there targeted destinations.
 *
 * @see        RecursiveDirectoryIterator
 */
class sfFilebasePluginUploadedFilesManager
{
  /**
   * @var array
   */
  protected static $UPLOADED_FILES = null;
  
  /**
   * @var sfFilebasePlugin $filebase
   */
  protected $filebase = null;

  /**
   * Constructor
   * 
   * @param sfFilebasePlugin $filebase
   */
  public function __construct(sfFilebasePlugin $filebase)
  {
    $this->filebase = $filebase;
  }

  /**
   * Checks if a variable contains a valid array that represents
   * an uploaded file.
   *
   * @return boolean true if file is valid
   */
  public static function isUploadedFile($file)
  {
    return
        is_array($file) &&
        array_key_exists('tmp_name', $file) &&
        array_key_exists('name', $file) &&
        array_key_exists('type', $file) &&
        array_key_exists('error', $file) &&
        array_key_exists('size', $file);
  }

  /**
   * Creates and returns a new instance of
   * sfFilebasePluginUploadedFile filled with the
   * values from the $values array.
   * 
   * @param array $values
   * @return sfFilebasePluginUploadedFile
   */

  public static function produceUploadedFile(array $values, $path = null)
  {
    return new sfFilebasePluginUploadedFile($values['name'], $values['type'], $values['tmp_name'], $values['size'], $path, $values['error']);
  }

  /**
   * Returns the data describing
   * uploaded files to handle by
   * move uploaded files.
   *
   * @param  mixed $index: The array index. If none given, the whole
   *                       transformed file will be returned.
   * @return array sfFilebasePluginUploadedFile $files
   */
  public function getUploadedFiles($index = null)
  {
    // Look for cached upload-info
    if(is_array(self::$UPLOADED_FILES))
    {
      return $index === null ? self::$UPLOADED_FILES : self::$UPLOADED_FILES[$index];
    }
    
    $files = array();
    foreach ($_FILES AS $i => $entry)
    {
      if(self::isUploadedFile($entry))
      {
        $names      = array();
        $types      = array();
        $tmp_names  = array();
        $errors     = array();
        $sizes      = array();
        // field, can be multi dimensional.
        if(is_array($entry['tmp_name']))
        {
          $names      = $entry['name'];
          $types      = $entry['type'];
          $tmp_names  = $entry['tmp_name'];
          $errors     = $entry['error'];
          $sizes      = $entry['size'];
        }

        // single file
        else
        {
          $names      = array($entry['name']);
          $types      = array($entry['type']);
          $tmp_names  = array($entry['tmp_name']);
          $errors     = array($entry['error']);
          $sizes      = array($entry['size']);
        }
        $files[$i] = $this->recurseFilesArray($names, $tmp_names, $types, $errors, $sizes);
      }
    }
    self::$UPLOADED_FILES = $files;
    return $this->getUploadedFiles($index);
  }

  /**
   * Recurses Uploaded Files array and populates
   * files return value from getUploadedFiles()
   *
   * @see getUploadedFiles
   * @todo transform into closure in php5.3
   * @param array $names
   * @param array $tmp_names
   * @param array $types
   * @param array $errors
   * @param array $sizes
   * @return array sfFilebasePluginUploadedFile $files
   */
  private function recurseFilesArray(array $names, array $tmp_names, array $types, array $errors, array $sizes)
  {
    $files = array();
    foreach($names AS $i => $name)
    {
      if(is_array($name))
      {
        $files[$i] = $this->recurseFilesArray($name, $tmp_names[$i], $types[$i], $errors[$i], $sizes[$i]);
      }
      else
      {
        $files[$i] = new sfFilebasePluginUploadedFile($name, $types[$i], $tmp_names[$i], $sizes[$i], null, $errors[$i]);
      }
    }
    return $files;
  }

  /**
   * Moves all uploaded files to the specified
   * destination directory.
   *
   * @see   sfFilebasePlugin::moveUploadedFile()
   * @param mixed sfFilebasePluginDirectory | string $destination
   * @param boolean $override
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @return array sfFilebasePluginFile: The uploaded files
   */
  public function moveAllUploadedFiles($destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array())
  {
    $files = array();
    foreach(new RecursiveIteratorIterator(
      new RecursiveArrayIterator($this->getUploadedFiles()),
      RecursiveIteratorIterator::SELF_FIRST
    ) AS $item)
    {
      if($item instanceof sfFilebasePluginUploadedFile)
      {
        $files [] = $this->moveUploadedFile($item, $destination_directory, $override, $chmod, $inclusion_rules, $exclusion_rules);
      }
    }
    return $files;
  }

  /**
   * Moves an uploaded File to specified Destination.
   * Inclusion and exclusion rules consist of regex-strings,
   * they are used to check the target filenames against them.
   * Exclusion:   Matched filenames throw exceptions.
   * Inclusion:  Matched filenames will be passed as valid filenames.
   *
   * You should wrap this method into a try-catch block because many
   * things can go wrong during or after the upload, so if you upload
   * many files and did not validate them before, this would be a good
   * starting point do to some "low level" validation.
   *
   * $destination can be a string (absolute or relative pathname) or an
   * instance of sfFilebasePluginFile, it is the directory, not the full pathName of
   * the new file. The file can be renamed by setting $file_name, otherwise
   * the original name will be taken as filename.
   *
   * @param mixed $tmp_file
   * @param mixed $destination_directory: The directory the file will be moved in.
   * @param boolean $allow_overwrite True if existing files should be overwritten
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @param string $file_name: If given, file will be renamed when moving.
   * @throws sfFilebasePluginException
   * @return sfFilebasePluginFile $moved_file
   */
  public function moveUploadedFile(sfFilebasePluginUploadedFile $tmp_file, $destination_directory, $allow_overwrite = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array(), $file_name = null)
  {
    $destination_directory = $this->filebase->getFilebaseFile($destination_directory);

    // Error handling
    if($tmp_file->isError())
    {
      switch($tmp_file->getError())
      {
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_EXTENSION:
          throw new sfFilebasePluginException('Upload error thrown by extension.', sfFilebasePluginUploadedFile::UPLOAD_ERR_EXTENSION);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_PARTIAL:
          throw new sfFilebasePluginException('Upload error: Upload interrupted.', sfFilebasePluginUploadedFile::UPLOAD_ERR_PARTIAL);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_CANT_WRITE:
          throw new sfFilebasePluginException('Upload error: Cannot write temporary file.', sfFilebasePluginUploadedFile::UPLOAD_ERR_CANT_WRITE);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_EXTENSION:
          throw new sfFilebasePluginException('Upload error: Extension error.', sfFilebasePluginUploadedFile::UPLOAD_ERR_EXTENSION);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_FORM_SIZE:
          throw new sfFilebasePluginException('Upload error: Form size exceeded.', sfFilebasePluginUploadedFile::UPLOAD_ERR_FORM_SIZE);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_INI_SIZE:
          throw new sfFilebasePluginException('Upload error: Ini size exceeded.', sfFilebasePluginUploadedFile::UPLOAD_ERR_INI_SIZE);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_FILE:
          throw new sfFilebasePluginException('Upload error: no file was uploaded.', sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_FILE);
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_TMP_DIR:
          throw new sfFilebasePluginException('Upload error: There is no temp dir.', sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_TMP_DIR);
          return false;
      }
    }

    // Validation, excuting inclusion rules
    foreach($inclusion_rules AS $rule)
    {
      if(!preg_match($rule, $tmp_file->getOriginalName()))
      {
        throw new sfFilebasePluginException(sprintf('Inclusion-Validation failed while uploading file %s: Rule %s', $tmp_file->getOriginalName(), $rule));
      }
    }

    // Valdating using exclusion rules
    foreach($exclusion_rules AS $rule)
    {
      if(preg_match($rule, $tmp_file->getOriginalName()))
      {
        throw new sfFilebasePluginException(sprintf('Exclusion-Validation failed while uploading file %s: Rule %s', $tmp_file->getOriginalName(), $rule));
      }
    }

    if(!$destination_directory instanceof sfFilebasePluginDirectory) throw new sfFilebasePluginException(sprintf('Destination %s is not a directory.', $destination_directory->getPathname()));

    $destination = null;
    // Filename given? Rename file...
    if($file_name === null)
    {
      $destination = $this->filebase->getFilebaseFile($destination_directory->getPathname() . '/' . $tmp_file->getOriginalName());
    }
    else
    {
      $file_name = (string)$file_name;
      if(strlen($file_name>0))
      {
        $destination = $this->filebase->getFilebaseFile($destination_directory->getPathname() . '/' . $file_name);
      }
    }
    
    if(!$this->filebase->isInFilebase($destination))                      throw new sfFilebasePluginException(sprintf('Destination %s does not lie within Filebase %s, access denied due to security reasons.', $destination->getPathname(), $this->filebase->getPathname()));

    if($destination->fileExists())
    {
      if($allow_overwrite)
      {
        if(!$destination->isWritable())   throw new sfFilebasePluginException(sprintf('File %s is write protected.', $destination->getPathname()));
      }
      else throw new sfFilebasePluginException(sprintf('Destination file %s already exists', $destination->getPathname()));
    }
    else
    {
      if(!$destination_directory->isWritable()) throw new sfFilebasePluginException(sprintf('Destination directory %s is write protected', $destination_directory->getPathname()));
    }
    if(!@move_uploaded_file($tmp_file->getTempName(), $destination->getPathname()))  throw new sfFilebasePluginException (sprintf('Error while moving uploaded file %s', $tmp_file->getOriginalName()));
    $destination->chmod($chmod);
    return $destination;
  }
}
