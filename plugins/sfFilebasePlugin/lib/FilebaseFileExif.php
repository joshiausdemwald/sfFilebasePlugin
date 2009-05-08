<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilebaseFileExif
 *
 * @author    Joshi
 * @copyright Joshi
 * @package   de.optimusprime.util.filebase
 */
class FilebaseFileExif extends Struct
{

  /**
   * @var FilebaseFile $file
   */
  private $imageFile;

  /**
   * Constructor
   * @param FilebaseFile $file
   */
  public function __construct(FilebaseFile $file)
  {
    $this->imageFile = $file;
    parent::__construct(exif_read_data($file));
  }

  /**
   *
   * @return FilebaseFile $file
   */
  public function getImageFile()
  {
    return $this->imageFile;
  }
}