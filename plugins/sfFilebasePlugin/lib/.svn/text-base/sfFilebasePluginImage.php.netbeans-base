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
 * sfFilebasePluginImage is a special sfFilebasePluginFile which represents an
 * image file.
 *
 * @see        SplFileInfo
 * @see        sfFilebasePluginFile
 */
class sfFilebasePluginImage extends sfFilebasePluginFile
{
  const ADAPTER_GD = 'GD';
  const ADAPTER_IMAGICK = 'ImageMagick';

  public static $DEFAULT_ADAPTER = sfFilebasePluginImage::ADAPTER_GD;

  /**
   * @var sfImage $image
   */
  protected $sfImage;

  /**
   * Returns a cached thumbnail image.
   * $width, $height, $method='fit', $background=null
   * @return sfFilebasePluginImage $image
   */
  public function getThumbnail($width, $height, $method = 'fit', $background = null, $quality = 60, $mime = 'image/png')
  {
    $save_path = $this->filebase[$this->getFilebase()->getCacheDirectory()->getPathname() . '/' . $this->getFilename() . '_' . $width . '_' . $height . '_' . $quality . '.' . sfFilebasePluginUtil::getExtensionByMime($mime)];
    if($save_path->fileExists())
    {
      return $save_path;
    }
    $image = $this->thumbnail($width, $height, $method, $background)
      ->setQuality($quality)
      ->setMimeType($mime);
    return $image->saveAs($save_path);
  }

  /**
   * Reads exif info from image files
   *
   * @return sfFilebasePluginFileExif $exif
   */
  public function readExif()
  {
    if(!$this->fileExists()) throw new sfFilebasePluginException(sprintf('File %s does not exist.', $this->getPathname()));
    if(!$this->isImage()) throw new sfFilebasePluginException(sprintf('File %s is no image file.', $this->getPathname()));
    if(!$this->isReadable()) throw new sfFilebasePluginException(sprintf('File %s is read protected.', $this->getPathname()));
    if(!function_exists('exif_imagetype')) throw new sfFilebasePluginException(sprintf('Exif extension currently not installed. Follow the instructions on http://php.net/manual/en/exif.setup.php to install it.'));

    $image_type = exif_imagetype($this);
    if($image_type == IMAGETYPE_JPEG || $image_type == IMAGETYPE_TIFF_II || $image_type == IMAGETYPE_TIFF_MM)
    {
       return new sfFilebasePluginFileExif($this);
    }
    else throw new sfFilebasePluginException(sprintf('Image type %s hat no build-in support for exif metadata.', $this->getExtension()));
  }

  /**
   * Magic method. This allows the calling of execute transform methods on sfImageTranform objects.
   *
   * @method
   * @see sfImage::__call
   * @param string $name the name of the transform, sfImage<NAME>
   * @param array Arguments for the transform class execute method
   * @return sfFilebasePluginImage
   */
  public function __call($name, $arguments)
  {
    $this->sfImage = $this->getSfImage()->__call($name, $arguments);
    // So we can chain transforms return the reference to itself
    return $this;
  }

  /**
   * Returns the sfImage instance associated with this
   * file info object.
   *
   * @return sfImage $image
   */
  public function getSfImage($adapter = null)
  {
    $adapter === null && $adapter = self::$DEFAULT_ADAPTER;
    if(!$this->sfImage)
    {
      $this->sfImage = new sfImage($this->getPathname(), $this->getMimeType(''), $adapter);
    }
    return $this->sfImage;
  }

  /**
   * Saves the image to disk
   * Saves the image to the filesystem
   *
   * @see sfImage::save();
   * @access public
   * @param string
   * @return boolean
   */
  public function save()
  {
    $this->getSfImage()->save();
    return $this;
  }

  /**
   * Saves the image to the specified filename
   *
   * Allows the saving to a different filename
   *
   * @access public
   * @see sfImage::saveAs()
   * @param string Filename
   * @param string MIME type
   * @return sfFilebasePluginImage
   */
  public function saveAs($filename, $mime = null)
  {
    $mime === null && $mime = $this->getMimeType('');
    $image = $this->getSfImage()->saveAs($filename, $mime);
    return new sfFilebasePluginImage($image->getAdapter()->getFilename(), $this->getFilebase());
  }

 /**
   * Returns the image pixel width
  *  @see sfImage::getWidth()
   * @return integer
   */
  public function getWidth()
  {
    return $this->getSfImage()->getAdapter()->getWidth();
  }

  /**
   * Returns the image height
   * @see sfImage::getHeight
   * @return integer
   */
  public function getHeight()
  {
    return $this->getSfImage()->getAdapter()->getHeight();
  }

  /**
   * Sets the image quality
   * @see sfImage::setQuality()
   * @param integer Valid range is from 0 (worst) to 100 (best)
   * @return sfFilebasePluginImage
   */
  public function setQuality($quality)
  {
    $this->getSfImage()->getAdapter()->setQuality($quality);
    return $this;
  }

  /**
   * Sets the MIME type
   * @see sfImage::setMIMEType()
   * @param string
   */
  public function setMimeType($mime)
  {
    $this->getSfImage()->setMIMEType($mime);
    return $this;
  }

  /**
   * Returns the image quality
   * @see sfImage:getQuality()
   * @return string
   */
  public function getQuality()
  {
    return $this->getSfImage()->getAdapter()->getQuality();
  }

  /**
   * Loads image from a string
   *
   * Loads the image from a string
   *
   * @see sfImage::loadString
   * @access public
   * @param string Image string
   * @return sfFilebasePluginImage
   */
  public function loadString($string)
  {
    $this->sfImage = $this->getSfImage()->loadString($string);
    return $this;
  }

  /**
   * Converts the image to a string
   * Returns the image as a string
   *
   * @see sfImage::toString()
   * @access public
   * @return string
   */
  public function getBinaryString()
  {
    return $this->getSfImage()->toString();
  }
}