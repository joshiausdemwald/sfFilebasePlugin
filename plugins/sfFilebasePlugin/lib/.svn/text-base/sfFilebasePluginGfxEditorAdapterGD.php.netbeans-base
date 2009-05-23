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
 * Adapter for transforming images using the gd1/2 library
 *
 * @todo       Implement improved image editing capabilities.
 */
class sfFilebasePluginGfxEditorAdapterGD implements sfFilebasePluginGfxEditorAdapterInterface
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
   * @var resource $resource;
   */
  protected $source_resource;

  /**
   * @var resource $resource
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
    $this->funcs['imagecreatefromtruecolor'] = function_exists('imagecreatefromtruecolor') ?
      'imagecreatefromtruecolor' :
      'imagecreate';
    $this->funcs['imagecopyresampled'] = function_exists('imagecopyresampled') ? 
      'imagecopyresampled' :
      'imagecopyresized';
    $this->gfxEditor = $gfxEditor;
  }

  /**
   *
   * @return boolean true if platform supports gd
   */
  public function isSupported()
  {
    return extension_loaded('gd');
  }

  /**
   * @param FimebaseImage $image
   */
  public function setSource(sfFilebasePluginImage $source)
  {
    $this->source = $source;
    switch($source->getMimeType())
    {
      case 'image/pjpeg':
      case 'image/jpeg':
        $this->source_resource = imagecreatefromjpeg($this->source->getPathname());
        return;
      case 'image/gif':
        $this->source_resource = imagecreatefromgif($this->source->getPathname());
        return;
      case 'image/x-png':
      case 'image/png':
        $this->source_resource = imagecreatefrompng($this->source->getPathname());
        return;
    }
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
  }

  /**
   * Saves the image as destination path name to disc.
   *
   * @param integer $chmod
   * @return sfFilebasePluginImage $destination
   */
  public function save($chmod = 0777)
  {
    if(!is_resource($this->destination_resource)) throw new sfFilebasePluginException('Nothing to save.');
    switch($this->destination->getMimeType())
    {
      case 'image/pjpeg':
      case 'image/jpeg':
        imagejpeg($this->destination_resource, $this->destination->getPathname(), $this->destinationQuality);
        break;
      case 'image/gif':
        imagegif($this->destination_resource, $this->destination->getPathname());
        break;
      case 'image/x-png':
      case 'image/png':
        imagepng($this->destination_resource, $this->destination->getPathname(), round($this->destinationQuality/10), PNG_ALL_FILTERS);
        break;
    }
    $this->destination->chmod($chmod);
    return $this->destination;
  }

  /**
   * Frees memory that was reserved for image
   * manipulation.
   */
  public function destroy()
  {
    imagedestroy($this->destination_resource);
    imagedestroy($this->source_resource);
    $this->destination_resource  = null;
    $this->target_resource       = null;
    $this->destination            = null;
    $this->source                 = null;
  }

  /**
   * Resizes the source image.
   *
   * Uses some ideas from Adrian Mummey
   * <http://www.mummey.org/2008/11/transparent-gifs-with-php-and-gd/comment-page-1/#comment-264>
   * to preserve transparent color on gifs images.
   *
   * @param array $dimensions
   * @return boolean true if image resizing was successful
   */
  public function resize(array $dimensions)
  {
    if(!is_resource($this->source_resource) || !$this->destination instanceof sfFilebasePluginImage) throw new sfFilebasePluginException('You must set a source and a destination image to resize.');
    
    $image_data = $this->gfxEditor->getScaledImageData($this->source, $dimensions);

    $width                = $image_data['orig_width'];
    $height               = $image_data['orig_height'];
    $new_width            = $image_data['new_width'];
    $new_height           = $image_data['new_height'];
    $mime                 = $image_data['mime'];

    $this->destination_resource = imagecreatetruecolor($new_width, $new_height);

    if($this->preserve_transparency && ($mime == 'image/gif' || $mime == 'image/png' || $mime == 'image/x-png'))
    {
      if($mime == 'image/gif')
      {
        imagealphablending($this->destination_resource, false);
        $transparent1 = imagecolorallocatealpha($this->destination_resource, 255, 255, 255, 127);
        imagefilledrectangle($this->destination_resource, 0, 0, $new_width, $new_height, $transparent1);
        imagecolortransparent($this->destination_resource, $transparent1);
      }
      else
      {
        imagealphablending($this->destination_resource, false);
      }
      imagesavealpha($this->destination_resource, true);
      switch ($mime)
      {
        case 'image/png':
        case 'image/x-png':
          $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          break;
        case 'image/gif':
          imagecopyresized($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          break;
      }
    }
    else
    {
      switch ($mime)
      {
        case  'image/jpeg':
        case  'image/pjpeg':
          $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          break;
        case 'image/x-png':
        case 'image/png':
          $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          break;
        case 'image/gif':
          $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
          break;
      }
    }
    return true;
  }

  /**
   * Rotates an image to $deg degree
   *
   * @uses  imagerotate():     Hinweis: Diese Funktion steht nur zur Verfügung, wenn PHP mit der GD Bibliothek übersetzt wurde, die mit PHP zusammen erhältlich ist.
   * @todo Implement fallback. Its a performance issue.
   * @experimental
   * @param float  $deg: The amount to rotate
   * @param string $bgcolor: The background color in html hexadecimal notation
   * @return sfFilebasePluginImage $image: THe rotated image
   */
  public function rotate($deg,  $bgcolor)
  {
    $bgcolor=0;
    if(!function_exists('imagerotate')) throw new sfFilebasePluginException('Imagerotate() is not supported by your gd-version, you must compile php with the pre-built gd2-library.');
    if(!is_resource($this->source_resource) || !$this->destination instanceof sfFilebasePluginImage) throw new sfFilebasePluginException('You must set a source and a destination image to resize.');
    $this->destination_resource = imagerotate($this->source_resource, $deg, sfFilebasePluginUtil::parseHTMLColor($bgcolor), true);
    if(!is_resource($this->destination_resource)) throw new sfFilebasePluginException(sprintf('Failed to rotate image %s.', $this->source));
    return true;
  }

  /**
   * Sets the flag that determins if the processor should preserve transparency
   * during the image manipulation.
   *
   * @param boolean $preserve_transparency
   */
  public function setPreserveTransparency($preserve_transparency)
  {
    $this->preserve_transparency = $preserve_transparency;
  }
}