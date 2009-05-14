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
 * Library for performing image transformations using image-magick
 * in combination with php's imagick extension
 *
 * @todo       Implement improved image editing capabilities.
 */
class sfFilebasePluginGfxEditorAdapterIMagick implements sfFilebasePluginGfxEditorAdapterInterface
{
  /**
   * @var sfFilebasePluginImage $image
   */
  protected $source;

  /**
   *
   * @var sfFilebasePluginImage $image
   */
  protected $destination;

  /**
   * @var Imagick $resource;
   */
  protected $source_resource;

  /**
   * @var Imagick $resource
   */
  protected $destination_resource;

  /**
   * Reference to editor.
   *
   * @var sfFilebasePluginGfxEditor $editor
   */
  protected $gfxEditor;

  /**
   * @var integer Quality in %
   */
  protected $destinationQuality = 80;

  /**
   * Compatibility layer to switch on the fly between
   * gd versions.
   * @var array $funcs
   */
  protected $funcs = array();

  /**
   * If set to true, transparent images will not loose their transparent
   * color definition during processing
   * 
   * @var boolean $preserve_transparency
   */
  protected $preserve_transparency;

  /**
   * @param sfFilebasePluginGfxEditor $editor
   */
  public function initialize(sfFilebasePluginGfxEditor $gfxEditor)
  {
    $this->gfxEditor = $gfxEditor;
  }

  /**
   *
   * @return boolean true if platform supports Imagick
   */
  public function isSupported()
  {
    return class_exists('Imagick');
  }

  /**
   * @param FimebaseImage $image
   */
  public function setSource(sfFilebasePluginImage $source)
  {
    $this->source = $source;
    $this->source_resource = new Imagick($source->getPathname());
  }

  /**
   * Sets the destination quality.
   * @param integer $quality
   */
  public function setQuality($quality)
  {
    $this->destinationQuality = $quality;
  }

  /**
   * Sets the destination path.

   * @param sfFilebasePluginImage $destination
   */
  public function setDestination(sfFilebasePluginImage $destination)
  {
    $this->destination = $destination;
    $this->destination_resource = $this->source_resource->clone();
  }

  /**
   * Saves the image as destination path name to disc.
   *
   * @param integer $chmod
   * @return sfFilebasePluginImage $destination
   */
  public function save($chmod = 0777)
  {
    if(!$this->destination_resource instanceof Imagick) throw new sfFilebasePluginException('Nothing to save.');
    $this->destination_resource->writeImage($this->destination->getPathname());
    return $this->destination;
  }

  /**
   * Frees memory that was reserved for image
   * manipulation.
   */
  public function destroy()
  {
    $this->destination_resource  = null;
    $this->target_resource       = null;
    $this->destination            = null;
    $this->source                 = null;
  }

  /**
   * Resizes the source image.
   * 
   * @param array $dimensions
   * @return true if image resizing was successful
   */
  public function resize(array $dimensions)
  {
    if(!$this->source_resource instanceof Imagick || !$this->destination instanceof sfFilebasePluginImage) throw new sfFilebasePluginException('You must set a source and a destination image to resize.');

    $image_data = $this->gfxEditor->getScaledImageData($this->source, $dimensions);

    $width                = $image_data['orig_width'];
    $height               = $image_data['orig_height'];
    $new_width            = $image_data['new_width'];
    $new_height           = $image_data['new_height'];
    $mime                 = $image_data['mime'];

    $this->destination_resource->thumbnailImage($new_width, $new_height);
    return true;
  }

  /**
   * Rotates an image to $deg degree
   * @param integer $deg: The amount to rotate
   * @param string $bgcolor: The background color in html hexadecimal notation
   * @return sfFilebasePluginImage $image: THe rotated image
   */
  public function rotate($deg, $bgcolor)
  {
     $ret_val = $this->destination_resource->rotateImage(new ImagickPixel($bgcolor), $deg);
     if($ret_val !== true) throw new sfFilebasePluginException(sprintf('Failed to rotate image %s.', $this->source));
     return $ret_val;
  }

  /**
   * Sets the flag that determins if the processor should preserve transparency
   * during the image manipulation.
   *
   * This is not needed by imagick, perhapts later on for fine tuning.
   *
   * @param boolean $preserve_transparency
   */
  public function setPreserveTransparency($preserve_transparency)
  {
    $this->preserve_transparency = $preserve_transparency;
  }
}