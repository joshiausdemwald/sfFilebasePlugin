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
 * sfFilebasePluginGfxEditor edits images. Ärm. Ok, it can create thumbnails.
 * 
 * @todo       Implement improved image editing capabilities.
 */
class sfFilebasePluginGfxEditor
{
  /**
   * List of available adapters,
   * set by registerAdapter().
   * @var array sfFilebasePluginGfxEditorAdapterInterface
   */
  protected static $available_adapters = array();

  /**
   * @var sfFilebasePlugin $filebase
   */
  protected $filebase;

  /**
   * The instance that handles
   * the image transformations.
   *
   * @var sfFilebasePluginGfxEditorAdapterInterface $adapter
   */
  protected $adapter;

  /**
   * Constructor.
   *
   * At this point, all available adapters are checked. If no fitting
   * adapter is found, an exception will be thrown.
   *
   * @param sfFilebasePlugin $filebase
   * @throws sfFilebasePluginException
   */
  function __construct(sfFilebasePlugin $filebase, $preferred_adapter = 'sfFilebasePluginGfxEditorAdapterIMagick')
  {
    $this->filebase = $filebase;
    
    if(array_key_exists($preferred_adapter, self::$available_adapters))
    {
      $this->adapter = clone(self::$available_adapters[$preferred_adapter]);
      $this->adapter->initialize($this);
    }
    else
    {
      throw new sfFilebasePluginException(sprintf('Prefered sfFilebasePluginGfxEditorAdapter %s has never been registered.', $preferred_adapter));
    }
    $iter = new ArrayIterator(self::$available_adapters);
    while(!$this->adapter->isSupported())
    {
      if(!$iter->current())
      {
        throw new sfFilebasePluginException('No adapter found that supports image transformation on your platform. Please consider to upgrading your platform.');
      }
      
      if($iter->key() != get_class($this->adapter))
      {
        $this->adapter = clone $iter->current();
        $this->adapter->initialize($this);
      }
      $iter->next();
    }
  }

  /**
   * Registers a new Adapter. Registered Adapters
   * are checked to be supported and automatically
   * chosen if so. They must be registered so the
   * sfFilebasePluginGfxEditor knows from which adapter she may
   * choose.
   *
   * @param string $adapter: Classname of Adapter
   */
  public static function registerAdapter($class_name)
  {
    if(!array_key_exists($class_name, self::$available_adapters))
    {
      $candidate = new $class_name();
      if(!$candidate instanceof sfFilebasePluginGfxEditorAdapterInterface) throw new sfFilebasePluginException(sprintf('Adapter %s must be an instanceof sfFilebasePluginGfxEditorAdapterInterface.', $class_name));
      self::$available_adapters[$class_name] = $candidate;
    }
  }

  /**
   * Resamples an image using php internal gd-functions. Used by thumbnail and resize
   * method.
   *
   * Dimensions is a 2-dim array with height, width or both, the other sides size is
   * calculated. It can have string-keys (width/height) or integer (0=>width,1=>height)
   *
   * @param mixed sfFilebasePluginImage | string $src: The souce image
   * @param mixed sfFilebasePluginImage | string $dst: The destination image, may be the same as $src
   * @param integer $width: The original width
   * @param integer $height: The original height
   * @param integer $dimensions: The new dimensions array('width'=>optional, 'height'=>optional, 0=>width, 1=>height)
   * @param boolean $overwrite: source image may be overwritten if set to true
   * @param integer $quality The sample-quality in percent
   * @param integer $preserve_transparency: If set to true, transparency of
   *                png or gif images shall be preserved.
   * @return sfFilebasePluginImage $image: The resampled image.
   */
  public function imageCopyResampled($src, $dst, array $dimensions, $overwrite = false, $quality = 60, $preserve_transparency = true)
  {
    $quality = (int) $quality;

    $src = $this->filebase->getFilebaseFile($src);
    $dst = $this->filebase->getFilebaseFile($dst);
    $dst_dir = $this->filebase->getFilebaseFile($dst->getPath());

    // Check source and target
    if(!$this->filebase->isInFilebase($src))                        throw new sfFilebasePluginException(sprintf('Source image %s does not belong to filebase %s, access restricted due to security issues.', $src, $this->filebase));
    if(!$src->isImage())                                            throw new sfFilebasePluginException(sprintf('Source file %s is not an image.', $src));
    if(!$this->filebase->getIsSupportedImage($src))                 throw new sfFilebasePluginException(sprintf('Source image format %s is unsupported.', $src));
    if(!$src->fileExists())                                         throw new sfFilebasePluginException(sprintf('Source image %s does not exist.', $src));
    if(!$src->isReadable())                                         throw new sfFilebasePluginException(sprintf('Source image %s is read protected.', $src));

    if(!$this->filebase->isInFilebase($dst))                        throw new sfFilebasePluginException(sprintf('Destination image %s does not belong to filebase, access restricted due to security issues.', $dst));
    
    if($dst->fileExists())
    {
      if($overwrite)
      {
         if(!$dst->isWritable()) throw new sfFilebasePluginException(sprintf('Destination image %s does exist but is write protected.', $dst));
      }
      else throw new sfFilebasePluginException(sprintf('Destination image %s already exists.', $dst));
    }
    else
    {
      if(!$dst_dir->isWritable())             throw new sfFilebasePluginException(sprintf('Destination directory %s is write protected.', $dst_dir));
    }

    if($quality < 0 || $quality > 100) throw new sfFilebasePluginException('Quality must be an intval out of 0 to 100');

    $this->adapter->setPreserveTransparency($preserve_transparency);
    $this->adapter->setQuality($quality);
    $this->adapter->setSource($src);
    $this->adapter->setDestination($dst);
    $this->adapter->resize($dimensions);
    $img = $this->adapter->save();
    $this->adapter->destroy();
    return $img;
  }

  /**
   * Rotates an image to $deg degree.
   *
   * @param  sfFilebasePluginImage $image: The image that shall be rotated.
   * @param  integer       $deg:   The amount in degree.
   * @param  string        $bgcolor: HTML-Hex-Color
   * @param integer $preserve_transparency: If set to true, transparency of
   *                png or gif images shall be preserved. The background color
   *                then will be transparent also.
   * @return sfFilebasePluginImage $image: The rotated image.
   */
  public function imageRotate(sfFilebasePluginImage $fileinfo, $deg, $bgcolor, $preserve_transparency)
  {
    $this->adapter->setPreserveTransparency($preserve_transparency);
    $this->adapter->setSource($fileinfo);
    $this->adapter->setDestination($fileinfo);
    $this->adapter->rotate($deg, $bgcolor);
    $img = $this->adapter->save();
    $this->adapter->destroy();
    return $img;
  }
  
  /**
   * Creates a Thumbnail named by md5-hash of the image
   * and its file ending.
   *
   * @param mixed sfFilebasePluginImage $fileinfo
   * @param array $dimensions = array(width, height)
   */
  public function createThumbnail(sfFilebasePluginImage $fileinfo, array $dimensions, $quality, $mime, $preserve_transparency = true)
  {
    // Check cache directory
    if(!$this->filebase->getCacheDirectory()->fileExists()) throw new sfFilebasePluginException(sprintf('Cache directory %s does not exist.', $this->filebase->getCacheDirectory()->getPathname()));

    // Check if original file is writable...
    if(!$fileinfo->fileExists())        throw new sfFilebasePluginException(sprintf('File %s does not exist', $fileinfo->getPathname()));
    if(!$fileinfo->isReadable())        throw new sfFilebasePluginException(sprintf('File %s is write protected.', $fileinfo->getPathname()));
    if(!$this->filebase->getIsSupportedImage($fileinfo))           throw new sfFilebasePluginException(sprintf('File %s is not an image.', $fileinfo));if(!$fileinfo->isImage())           throw new sfFilebasePluginException(sprintf('Image format %s is unsupported.', $fileinfo));
    if(!$this->filebase->isInFilebase($fileinfo)) throw new sfFilebasePluginException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $fileinfo->getPathname(), $this->filebase->getPathname()));
    $destination = $this->getThumbnailFileinfo($fileinfo, $dimensions, $mime);
    return new sfFilebasePluginThumbnail($this->imageCopyResampled($fileinfo, $destination, $dimensions, true, $preserve_transparency), $this->filebase, $fileinfo);
  }

  /**
   * Calculates and returns the properties (width/height) of a thumbail/scaled image.
   *
   * Return value is an array containing calculated width/height and extension.
   *
   * @param sfFilebasePluginImage $fileinfo
   * @param integer $new_width
   * @param integer $new_height
   * @throws sfFilebasePluginException
   * @return array $thumbnail_properties
   */
  public function getScaledImageData(sfFilebasePluginImage $image, array $dimensions)
  {
    $width      = 0;
    $height     = 0;
    $new_width  = null;
    $new_height = null;

     // @todo, den check mach ich auch beim copyResampled. Hier nur gebraucht für filename
    isset($dimensions[0])         && $dimensions['width']   = $dimensions[0];
    isset($dimensions[1])         && $dimensions['height']  = $dimensions[1];
    isset($dimensions['width'])   && (int)$dimensions['width']  > 0   && $new_width   = $dimensions['width'];
    isset($dimensions['height'])  && (int)$dimensions['height'] > 0   && $new_height  = $dimensions['height'];

    if($new_width === null && $new_height === null) throw new sfFilebasePluginException ('Dimensions are not properly set.');

    $extension = $image->getExtension();

    list($width, $height) = $image->getImagesize();
    
    if($new_height === null)
    {
      $new_height = round ($height * $new_width / $width);
    }
    elseif($new_width===null)
    {
      $new_width  = round($width * $new_height / $height);
    }
    return array ('orig_width' => $width, 'orig_height' => $height, 'new_width' => $new_width, 'new_height' => $new_height, 'extension' => $extension, 'mime' => sfFilebasePluginUtil::getMimeType($image));
  }

  /**
   * Returns filename for a cached thumbnail, calculated
   * by its properties and dimensions.
   *
   * @param sfFilebasePluginFile $file
   * @param array $thumbnail_properties
   * @return sfFilebasePluginImage $filename
   */
  public function getThumbnailFileinfo(sfFilebasePluginImage $file, $dimensions, $mime)
  {
    $thumbnail_properties = $this->getScaledImageData($file, $dimensions);
    // Wrap in sfFilebasePluginImage because isImage may return false if file does not exist.
    return new sfFilebasePluginThumbnail($this->filebase->getFilebaseFile($this->filebase->getCacheDirectory() . DIRECTORY_SEPARATOR . $this->filebase->getHashForFile($file) . '_' . $thumbnail_properties['new_width'] . '_' . $thumbnail_properties['new_height'] . '.' . (sfFilebasePluginUtil::getExtensionByMime($mime) === null ? $thumbnail_properties['extension'] : sfFilebasePluginUtil::getExtensionByMime($mime))), $this->filebase, $file);
  }

  /**
   * Returns current instance of sfFilebasePlugin.
   * 
   * @return sfFilebasePlugin $filebase
   */
  public function getFilebase()
  {
    return $this->filebase;
  }
}

sfFilebasePluginGfxEditor::registerAdapter('sfFilebasePluginGfxEditorAdapterGD');
sfFilebasePluginGfxEditor::registerAdapter('sfFilebasePluginGfxEditorAdapterIMagick');