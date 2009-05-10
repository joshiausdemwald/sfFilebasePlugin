<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * UploadedFilesManager holds instances of FilebaseUploadedFiles.
 * It provide methods to access these objects and moves their referenced
 * temp-files to there targeted destinations.
 *
 * @see        RecursiveDirectoryIterator
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class UploadedFilesManager
{
  /**
   * @var array
   */
  protected $uploadedFiles = null;
  
  /**
   * @var Filebase $filebase
   */
  protected $filebase = null;

  /**
   * Constructor
   * 
   * @param Filebase $filebase
   */
  public function __construct(Filebase $filebase)
  {
    $this->filebase = $filebase;
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
    // Look for cached upload-info
    if(is_array($this->uploadedFiles))
    {
      return $this->uploadedFiles;
    }
    
    //print_r($_FILES);
    $files = array();
    foreach ($_FILES AS $i => $entry)
    {
      if(
        is_array($entry) &&
        array_key_exists('tmp_name', $entry) &&
        array_key_exists('name', $entry) &&
        array_key_exists('type', $entry) &&
        array_key_exists('error', $entry) &&
        array_key_exists('size', $entry)
      )
      {
        $names = array();
        $types = array();
        $tmp_names = array();
        $errors = array();
        $sizes = array();
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
    $this->uploadedFiles = $files;
    return $this->getUploadedFiles();
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
   * @return array FilebaseUploadedFile $files
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
        $files[$i] = new FilebaseUploadedFile($name, $tmp_names[$i], $types[$i], $errors[$i], $sizes[$i], $this->filebase);
      }
    }
    return $files;
  }

  /**
   * Moves all uploaded files to the specified
   * destination directory.
   *
   * @see   Filebase::moveUploadedFile()
   * @param mixed FilebaseDirectory | string $destination
   * @param boolean $override
   * @param array $inclusion_rules
   * @param array $exclusion_rules
   * @return array FilebaseFile: The uploaded files
   */
  public function moveAllUploadedFiles($destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array())
  {
    $files = array();
    foreach(new RecursiveIteratorIterator(
      new RecursiveArrayIterator($this->getUploadedFiles()),
      RecursiveIteratorIterator::SELF_FIRST
    ) AS $item)
    {
      if($item instanceof FilebaseUploadedFile)
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
    $destination_directory = $this->filebase->getFilebaseFile($destination_directory);

    // Error handling
    if($tmp_file->isError())
    {
      switch($tmp_file->getError())
      {
        case FilebaseUploadedFile::UPLOAD_ERR_EXTENSION:
          throw new FilebaseException('Upload error thrown by extension.', FilebaseUploadedFile::UPLOAD_ERR_EXTENSION);
        case FilebaseUploadedFile::UPLOAD_ERR_PARTIAL:
          throw new FilebaseException('Upload error: Upload interrupted.', FilebaseUploadedFile::UPLOAD_ERR_PARTIAL);
        case FilebaseUploadedFile::UPLOAD_ERR_CANT_WRITE:
          throw new FilebaseException('Upload error: Cannot write temporary file.', FilebaseUploadedFile::UPLOAD_ERR_CANT_WRITE);
        case FilebaseUploadedFile::UPLOAD_ERR_EXTENSION:
          throw new FilebaseException('Upload error: Extension error.', FilebaseUploadedFile::UPLOAD_ERR_EXTENSION);
        case FilebaseUploadedFile::UPLOAD_ERR_FORM_SIZE:
          throw new FilebaseException('Upload error: Form size exceeded.', FilebaseUploadedFile::UPLOAD_ERR_FORM_SIZE);
        case FilebaseUploadedFile::UPLOAD_ERR_INI_SIZE:
          throw new FilebaseException('Upload error: Ini size exceeded.', FilebaseUploadedFile::UPLOAD_ERR_INI_SIZE);
        case FilebaseUploadedFile::UPLOAD_ERR_NO_FILE:
          throw new FilebaseException('Upload error: no file was uploaded.', FilebaseUploadedFile::UPLOAD_ERR_NO_FILE);
        case FilebaseUploadedFile::UPLOAD_ERR_NO_TMP_DIR:
          throw new FilebaseException('Upload error: There is no temp dir.', FilebaseUploadedFile::UPLOAD_ERR_NO_TMP_DIR);
          return false;
      }
    }

    // Validation, excuting inclusion rules
    foreach($inclusion_rules AS $rule)
    {
      if(!preg_match($rule, $tmp_file->getName()))
      {
        throw new FilebaseException(sprintf('Inclusion-Validation failed while uploading file %s: Rule %s', $tmp_file->getName(), $rule));
      }
    }

    // Valdating using exclusion rules
    foreach($exclusion_rules AS $rule)
    {
      if(preg_match($rule, $tmp_file->getName()))
      {
        throw new FilebaseException(sprintf('Exclusion-Validation failed while uploading file %s: Rule %s', $tmp_file->getName(), $rule));
      }
    }

    if(!$destination_directory instanceof FilebaseDirectory) throw new FilebaseException(sprintf('Destination %s is not a directory.', $destination_directory->getPathname()));

    $destination = null;
    // Filename given? Rename file...
    if($file_name === null)
    {
      $destination = $this->filebase->getFilebaseFile($destination_directory->getPathname() . '/' . $tmp_file->getName());
    }
    else
    {
      $file_name = (string)$file_name;
      if(strlen($file_name>0))
      {
        $destination = $this->filebase->getFilebaseFile($destination_directory->getPathname() . '/' . $file_name);
      }
    }

    if(!$this->filebase->isInFilebase($destination))                      throw new FilebaseException(sprintf('Destination %s does not lie within Filebase %s, access denied due to security reasons.', $destination->getPathname(), $this->filebase->getPathname()));

    if($destination->fileExists())
    {
      if($override)
      {
        if(!$destination->isWritable())   throw new FilebaseException(sprintf('File %s is write protected.', $destination->getPathname()));
      }
      else throw new FilebaseException(sprintf('Destination file %s already exists', $destination->getPathname()));
    }
    else
    {
      if(!$destination_directory->isWritable()) throw new FilebaseException(sprintf('Destination directory %s is write protected', $destination_directory->getPathname()));
    }

    if(!@move_uploaded_file($tmp_file->getTmpName()->getPathname(), $destination->getPathname()))  throw new FilebaseException (sprintf('Error while moving uploaded file %s', $tmp_file['tmp_name']));
    $destination->chmod($chmod);
    return $destination;
  }
}
