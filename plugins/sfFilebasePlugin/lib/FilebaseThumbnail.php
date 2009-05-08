<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilebaseThumbnail
 *
 * @author joshi
 */
class FilebaseThumbnail extends FilebaseImage
{
  /**
   * Holds a reference to the original
   * image of this thumbnail.
   * 
   * @var FilebaseImage $originalImage
   */
  protected $originalImage;

  /**
   * Constructor
   *
   * @param string $path_name
   * @param Filebase $filebase
   * @param FilebaseImage $original_image
   */
  public function __construct($path_name, Filebase $filebase, FilebaseImage $original_image)
  {
    parent::__construct($path_name, $filebase);
    $this->originalImage = $original_image;
  }

  /**
   * Returns the original image source of
   * the thumbnail.
   *
   * @return FilebaseImage $image
   */
  public function getOriginalImage()
  {
    return $this->originalImage;
  }
}
