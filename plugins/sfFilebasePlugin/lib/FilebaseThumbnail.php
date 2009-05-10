<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FilebaseThumbnail is a clone of a FilebaseImage that represents
 * a thumbnail
 * 
 * @package    de.optimusprime.sfFilebasePlugin
 * @see        SplFileInfo
 * @see        FilebaseImage
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
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
