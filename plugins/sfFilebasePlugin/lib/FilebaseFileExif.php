<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FilebaseFileExif represents exif-data of a FilebaseImage
 *
 * @see        exif_read_data()
 * @see        FilebaseImage
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
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