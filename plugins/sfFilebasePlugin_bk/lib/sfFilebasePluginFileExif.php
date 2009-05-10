<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfFilebasePluginFileExif represents exif-data of a sfFilebasePluginImage
 *
 * @see        exif_read_data()
 * @see        sfFilebasePluginImage
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class sfFilebasePluginFileExif extends sfFilebasePluginStruct
{

  /**
   * @var sfFilebasePluginFile $file
   */
  private $imageFile;

  /**
   * Constructor
   * @param sfFilebasePluginFile $file
   */
  public function __construct(sfFilebasePluginFile $file)
  {
    $this->imageFile = $file;
    parent::__construct(exif_read_data($file));
  }

  /**
   *
   * @return sfFilebasePluginFile $file
   */
  public function getImageFile()
  {
    return $this->imageFile;
  }
}