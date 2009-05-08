<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilebaseUploadedFile
 *
 * @author joshi
 */
class FilebaseUploadedFile
{
  const
    UPLOAD_ERR_OK         = UPLOAD_ERR_OK,
    UPLOAD_ERR_INI_SIZE   = UPLOAD_ERR_INI_SIZE,
    UPLOAD_ERR_FORM_SIZE  = UPLOAD_ERR_FORM_SIZE,
    UPLOAD_ERR_PARTIAL    = UPLOAD_ERR_PARTIAL,
    UPLOAD_ERR_NO_FILE    = UPLOAD_ERR_NO_FILE,
    UPLOAD_ERR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE,
    UPLOAD_ERR_EXTENSION  = UPLOAD_ERR_EXTENSION,
    UPLOAD_ERR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
  
  /**
   * @var Filebase $filebase
   */
  private $filebase;

  /**
   * @var string
   */
  private $name;

  /**
   * @var FilebaseFile $tmp_name
   */
  private $tmp_name;

  /**
   * @var string
   */
  private $type;

  /**
   * @var string
   */
  private $error;

  /**
   * @var string
   */
  private $size;

  /**
   * Constructor
   * 
   * @param string    $name
   * @param string    $tmp_name
   * @param string    $type
   * @param string    $error
   * @param string    $size
   * @param Filebase  $filebase
   */
  function __construct($name, $tmp_name, $type, $error, $size, Filebase $filebase)
  {
    $this->name     = $name;
    $this->tmp_name = $filebase->getFilebaseFile($tmp_name);
    $this->type     = $type;
    $this->error    = $error;
    $this->size     = $size;
    $this->filebase = $filebase;
  }

  /**
   * Moves an uploaded file into its final destination.
   * Inclusion and exclusion rules consist of regex-strings,
   * they are used to check the target filenames against them.
   * Exclusion:   Matched filenames throw exceptions.
   * Inclusion:   Matched filenames will be passed as valid filenames.
   *
   * $destination can be a string (absolute or relative (to filebase) pathname) or an
   * instance of FilebaseFile.
   *
   * @param mixed       FilebaseFile | string $destination_directory: Target directory to save the uploaded file
   * @param boolean     $overwrite: True if existing files should be overwritten
   * @param array       $inclusion_rules: Rules defining files to be explicetly allowed
   * @param array       $exclusion_rules: Rules defining files to be not allowed
   * @param string      $file_name: If given, uploaded file will be renamed after moving.
   */
  public function moveUploadedFile($destination_directory, $overwrite = true, $chmod=0777, array $inclusion_rules = array(), array $exclusion_rules = array(), $file_name = null)
  {
    return $this->filebase->moveUploadedFile($this, $destination_directory, $overwrite, $chmod, $inclusion_rules, $exclusion_rules, $file_name);
  }

  /**
   * Returns the mime type of the file,
   * if browser has provided one.
   *
   * @return string $type
   */
  public function getType()
  {
    return strtolower($this->type);
  }

  /**
   * Returns the file extension of the
   * file, if browser has provided a
   * mime-type.
   * @return string $extension
   */
  public function getExtension()
  {
    return FilebaseUtil::getExtensionByMime($this->getType());
  }

  /**
   * Returns the size of the uploaded file,
   * as provided by browser.
   * @return integer $size in bytes
   */
  public function getSize()
  {
    return $this->size;
  }

  /**
   * Returns true if an upload
   * error occured
   * @return boolean
   */
  public function isError()
  {
    return $this->error !== self::UPLOAD_ERR_OK;
  }

  /**
   * Returns the error code of
   * the uploaded file
   * @return integer $error_code
   */
  public function getError()
  {
    return $this->error;
  }

  /**
   * Returns the temp-name of the uploaded
   * file as an instance of FilebaseFile
   * 
   * @return FilebaseFile
   */
  public function getTmpName()
  {
    return $this->tmp_name;
  }

  /**
   * Returns the original name of
   * the file.
   * @return string $name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Moves the uploaded File to specified Destination.
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
   * @return FilebaseFile $moved_file;
   * @see Filbase::moveUploadedFile()
   */
  public function move($destination_directory, $override = true, $chmod=0777, array $inclusion_rules = array(), $exclusion_rules = array(), $file_name = null)
  {
    return $this->filebase->moveUploadedFile($this, $destination_directory, $override, $chmod, $inclusion_rules, $exclusion_rules, $file_name);
  }
}