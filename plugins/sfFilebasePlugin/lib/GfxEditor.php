<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * GfxEditor edits images. Ärm. Ok, it can create thumbnails.
 * 
 * @package    de.optimusprime.sfFilebasePlugin
 * @todo       Implement improved image editing capabilities.
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
class GfxEditor
{
  /**
   * List of available adapters,
   * set by registerAdapter().
   * @var array IGfxEditorAdapter
   */
  protected static $available_adapters = array();

  /**
   * @var Filebase $filebase
   */
  protected $filebase;

  /**
   * The instance that handles
   * the image transformations.
   *
   * @var IGfxEditorAdapter $adapter
   */
  protected $adapter;

  /**
   * Constructor.
   *
   * At this point, all available adapters are checked. If no fitting
   * adapter is found, an exception will be thrown.
   *
   * @param Filebase $filebase
   * @throws FilebaseException
   */
  function __construct(Filebase $filebase, $preferred_adapter = 'GfxEditorAdapterIMagick')
  {
    $this->filebase = $filebase;
    
    if(array_key_exists($preferred_adapter, self::$available_adapters))
    {
      $this->adapter = clone(self::$available_adapters[$preferred_adapter]);
      $this->adapter->initialize($this);
    }
    else
    {
      throw new FilebaseException(sprintf('Prefered GfxEditorAdapter %s has never been registered.', $preferred_adapter));
    }
    $iter = new ArrayIterator(self::$available_adapters);
    while(!$this->adapter->isSupported())
    {
      if(!$iter->current())
      {
        throw new FilebaseException('No adapter found that supports image transformation on your platform. Please consider to upgrading your platform.');
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
   * GfxEditor knows from which adapter she may
   * choose.
   *
   * @param string $adapter: Classname of Adapter
   */
  public static function registerAdapter($class_name)
  {
    if(!array_key_exists($class_name, self::$available_adapters))
    {
      $candidate = new $class_name();
      if(!$candidate instanceof IGfxEditorAdapter) throw new FilebaseException(sprintf('Adapter %s must be an instanceof GfxEditorAdapter.', $class_name));
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
   * @param mixed FilebaseImage | string $src: The souce image
   * @param mixed FilebaseImage | string $dst: The destination image, may be the same as $src
   * @param integer $width: The original width
   * @param integer $height: The original height
   * @param integer $dimensions: The new dimensions array('width'=>optional, 'height'=>optional, 0=>width, 1=>height)
   * @param boolean $overwrite: source image may be overwritten if set to true
   * @param integer $quality The sample-quality in percent
   */
  public function imageCopyResampled($src, $dst, array $dimensions, $overwrite = false, $quality = 60)
  {
    $quality = (int) $quality;

    $src = $this->filebase->getFilebaseFile($src);
    $dst = $this->filebase->getFilebaseFile($dst);
    $dst_dir = $this->filebase->getFilebaseFile($dst->getPath());

    // Check source and target
    if(!$this->filebase->isInFilebase($src))                        throw new FilebaseException(sprintf('Source image %s does not belong to filebase %s, access restricted due to security issues.', $src, $this->filebase));
    if(!$src->isImage())                                            throw new FilebaseException(sprintf('Source file %s is not an image.', $src));
    if(!$src->fileExists())                                         throw new FilebaseException(sprintf('Source image %s does not exist.', $src));
    if(!$src->isReadable())                                         throw new FilebaseException(sprintf('Source image %s is read protected.', $src));

    if(!$this->filebase->isInFilebase($dst))                        throw new FilebaseException(sprintf('Destination image %s does not belong to filebase, access restricted due to security issues.', $dst));
    
    if($dst->fileExists())
    {
      if($overwrite)
      {
         if(!$dst->isWritable()) throw new FilebaseException(sprintf('Destination image %s does exist but is write protected.', $dst));
      }
      else throw new FilebaseException(sprintf('Destination image %s already exists.', $dst));
    }
    else
    {
      if(!$dst_dir->isWritable())             throw new FilebaseException(sprintf('Destination directory %s is write protected.', $dst_dir));
    }

    if($quality < 0 || $quality > 100) throw new FilebaseException('Quality must be an intval out of 0 to 100');
    
    $this->adapter->setSource($src);
    $this->adapter->setDestination($dst);
    $this->adapter->resize($dimensions);
    $this->adapter->setQuality($quality);
    $img = $this->adapter->save();
    $this->adapter->destroy();
    return $img;
  }

  /**
   * Rotates an image to $deg degree.
   *
   * @param  FilebaseImage $image: The image that shall be rotated.
   * @param  integer       $deg:   The amount in degree.
   * @param  string        $bgcolor: HTML-Hex-Color
   * @return FilebaseImage $image: The rotated image.
   */
  public function imageRotate(FilebaseImage $fileinfo, $deg, $bgcolor)
  {
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
   * @param mixed FilebaseImage $fileinfo
   * @param array $dimensions = array(width, height)
   */
  public function createThumbnail(FilebaseImage $fileinfo, array $dimensions, $quality, $mime)
  {
    // Check cache directory
    if(!$this->filebase->getCacheDirectory()->fileExists()) throw new FilebaseException(sprintf('Cache directory %s does not exist.', $this->filebase->getCacheDirectory()->getPathname()));

    // Check if original file is writable...
    if(!$fileinfo->fileExists())        throw new FilebaseException(sprintf('File %s does not exist', $fileinfo->getPathname()));
    if(!$fileinfo->isReadable())        throw new FilebaseException(sprintf('File %s is write protected.', $fileinfo->getPathname()));
    if(!$fileinfo->isImage())           throw new FilebaseException(sprintf('File %s is not an image.', $fileinfo));
    if(!$this->filebase->isInFilebase($fileinfo)) throw new FilebaseException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $fileinfo->getPathname(), $this->filebase->getPathname()));
    $destination = $this->getThumbnailFileinfo($fileinfo, $dimensions, $mime);
    return $this->imageCopyResampled($fileinfo, $destination, $dimensions, true);
  }

  /**
   * Calculates and returns the properties (width/height) of a thumbail/scaled image.
   *
   * Return value is an array containing calculated width/height and extension.
   *
   * @param FilebaseImage $fileinfo
   * @param integer $new_width
   * @param integer $new_height
   * @throws FilebaseException
   * @return array $thumbnail_properties
   */
  public function getScaledImageData(FilebaseImage $image, array $dimensions)
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

    if($new_width === null && $new_height === null) throw new FilebaseException ('Dimensions are not properly set.');

    $extension = $image->getExtension();

    list($width, $height) = $image->getImagesize();

    if($new_height === null)
    {
      $new_height = round ($height * $new_width / $width);
    }
    else
    {
      $new_width  = round($width * $new_height / $height);
    }
    return array ('orig_width' => $width, 'orig_height' => $height, 'new_width' => $new_width, 'new_height' => $new_height, 'extension' => $extension, 'mime' => FilebaseUtil::getMimeByExtension($extension));
  }

  /**
   * Returns filename for a cached thumbnail, calculated
   * by its properties and dimensions.
   *
   * @param FilebaseFile $file
   * @param array $thumbnail_properties
   * @return FilebaseImage $filename
   */
  public function getThumbnailFileinfo(FilebaseImage $file, $dimensions, $mime)
  {
    $thumbnail_properties = $this->getScaledImageData($file, $dimensions);
    // Wrap in FilebaseImage because isImage may return false if file does not exist.
    return new FilebaseThumbnail($this->filebase->getFilebaseFile($this->filebase->getCacheDirectory() . DIRECTORY_SEPARATOR . $this->filebase->getHashForFile($file) . '_' . $thumbnail_properties['new_width'] . '_' . $thumbnail_properties['new_height'] . '.' . (FilebaseUtil::getExtensionByMime($mime) === null ? $thumbnail_properties['extension'] : FilebaseUtil::getExtensionByMime($mime))), $this->filebase, $file);
  }

  /**
   * Returns current instance of Filebase.
   * 
   * @return Filebase $filebase
   */
  public function getFilebase()
  {
    return $this->filebase;
  }
}

GfxEditor::registerAdapter('GfxEditorAdapterGD');
GfxEditor::registerAdapter('GfxEditorAdapterIMagick');