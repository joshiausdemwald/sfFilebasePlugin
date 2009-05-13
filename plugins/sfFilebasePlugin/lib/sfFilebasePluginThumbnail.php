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
 * sfFilebasePluginThumbnail is a clone of a sfFilebasePluginImage that represents
 * a thumbnail
 * 
 * @see        SplFileInfo
 * @see        sfFilebasePluginImage
 */
class sfFilebasePluginThumbnail extends sfFilebasePluginImage
{
  /**
   * Holds a reference to the original
   * image of this thumbnail.
   * 
   * @var sfFilebasePluginImage $originalImage
   */
  protected $originalImage;

  /**
   * Constructor
   *
   * @param string $path_name
   * @param sfFilebasePlugin $filebase
   * @param sfFilebasePluginImage $original_image
   */
  public function __construct($path_name, sfFilebasePlugin $filebase, sfFilebasePluginImage $original_image)
  {
    parent::__construct($path_name, $filebase);
    $this->originalImage = $original_image;
  }

  /**
   * Returns the original image source of
   * the thumbnail.
   *
   * @return sfFilebasePluginImage $image
   */
  public function getOriginalImage()
  {
    return $this->originalImage;
  }
}
