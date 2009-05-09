<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GfxEditor
 *
 * @author joshi
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
   * @var GfxEditorAdapter $adapter
   */
  protected $adapter;

  /**
   * Constructor.
   * 
   * @param Filebase $filebase
   * @throws FilebaseException
   */
  function __construct(Filebase $filebase, $preferred_adapter = 'GfxEditorAdapterGD')
  {
    $this->filebase = $filebase;
    
    if(array_key_exists($preferred_adapter, self::$available_adapters))
    {
      $this->adapter = self::$available_adapters[$preferred_adapter];
    }
    else
    {
      throw new FilebaseException(sprintf('Prefered GfxEditorAdapter %s has never been registered.', $preferred_adapter));
    }

    /*if(!$this->adapter->isSupported())
    {
      $iter = new ArrayIterator(self::$available_adapters);
      $iter->seek($preferred_adapter);
      while(!$this->adapter->isSupported())
      {
        $iter->next();
        if(!$iter->current())
        {
          $iter->rewind();
        }
        if($iter->key == $preferred_adapter)
        {
          throw new FilebaseException('No supported Adapters found, image transformation not possible.');
        }
        $this->adapter = $iter->current();
      }
    }*/
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
  public static function registerAdapter($adapter)
  {
    if(!array_key_exists($adapter, self::$available_adapters))
    {
      $candidate = new $adapter();
      if(!$candidate instanceof IGfxEditorAdapter) throw new FilebaseException(sprintf('Adapter %s must be an instanceof GfxEditorAdapter.', $adapter));
      self::$available_adapters[$adapter] = $candidate;
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

    $new_width  = null;
    $new_height = null;
    $height     = 0;
    $width      = 0;

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

    $image_data = $this->getScaledImageData($src, $dimensions);

    $width                = $image_data['orig_width'];
    $height               = $image_data['orig_height'];
    $new_width            = $image_data['new_width'];
    $new_height           = $image_data['new_height'];
    $extension            = $image_data['extension'];

    switch (strtolower($extension))
    {
      case  'jpg':
      case 'jpeg':
        $image = imagecreatefromjpeg($src->getPathname());
        $image_p = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dst->getPathname(), $quality);
        break;
      case 'png':
        $image = imagecreatefrompng($src->getPathname());
        $image_p = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagepng($image_p, $dst->getPathname(), round($quality/10));
        break;

      case 'gif':
        $image = imagecreatefromgif($src->getPathname());
        $image_p = imagecreate($new_width, $new_height);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($image_p, $dst->getPathname());
        break;
    }
    return $dst;
  }
  
  /**
   * Creates a Thumbnail named by md5-hash of the image
   * and its file ending.
   *
   * @param mixed FilebaseImage $fileinfo
   * @param array $dimensions = array(width, height)
   */
  public function createThumbnail(FilebaseImage $fileinfo, array $dimensions, $quality)
  {
    // Check cache directory
    if(!$this->filebase->getCacheDirectory()->fileExists()) throw new FilebaseException(sprintf('Cache directory %s does not exist.', $this->filebase->getCacheDirectory()->getPathname()));
    
    // Check if original file is writable...
    if(!$fileinfo->fileExists())        throw new FilebaseException(sprintf('File %s does not exist', $fileinfo->getPathname()));
    if(!$fileinfo->isReadable())        throw new FilebaseException(sprintf('File %s is write protected.', $fileinfo->getPathname()));
    if(!$fileinfo->isImage())           throw new FilebaseException(sprintf('File %s is not an image.', $fileinfo));
    if(!$this->filebase->isInFilebase($fileinfo)) throw new FilebaseException(sprintf('FilebaseFile %s does not belong to Filebase %s, cannot be deleted due to security issues', $fileinfo->getPathname(), $this->filebase->getPathname()));
    $destination = $this->getThumbnailFileinfo($fileinfo, $dimensions);
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
  protected function getScaledImageData(FilebaseImage $image, array $dimensions)
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
    return array ('orig_width' => $width, 'orig_height' => $height, 'new_width' => $new_width, 'new_height' => $new_height, 'extension' => $extension);
  }

  /**
   * Returns filename for a cached thumbnail, calculated
   * by its properties and dimensions.
   *
   * @param FilebaseFile $file
   * @param array $thumbnail_properties
   * @return FilebaseImage $filename
   */
  protected function getThumbnailFileinfo(FilebaseImage $file, $dimensions)
  {
    $thumbnail_properties = $this->getScaledImageData($file, $dimensions);
    // Wrap in FilebaseImage because isImage may return false if file does not exist.
    return new FilebaseThumbnail($this->filebase->getFilebaseFile($this->filebase->getCacheDirectory() . DIRECTORY_SEPARATOR . $this->filebase->getHashForFile($file) . '_' . $thumbnail_properties['new_width'] . '_' . $thumbnail_properties['new_height'] . '.' . $thumbnail_properties['extension']), $this->filebase, $file);
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