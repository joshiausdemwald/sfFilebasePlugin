<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GfxEditorAdapterGD
 *
 * @author joshi
 */
class GfxEditorAdapterGD implements IGfxEditorAdapter
{
  /**
   * @var FilebaseImage $image
   */
  protected $source;

  /**
   *
   * @var FilebaseImage $image
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
   * @var GfxEditor $editor
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
   * @param GfxEditor $editor
   */
  public function initialize(GfxEditor $gfxEditor)
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
  public function setSource(FilebaseImage $source)
  {
    $this->source = $source;
    switch($source->getMimeType())
    {
      case 'image/jpeg':
        $this->source_resource = imagecreatefromjpeg($this->source->getPathname());
        return;
      case 'image/gif':
        $this->source_resource = imagecreatefromgif($this->source->getPathname());
        return;
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
   
   * @param FilebaseImage $destination
   */
  public function setDestination(FilebaseImage $destination)
  {
    $this->destination = $destination;
  }

  /**
   * Saves the image as destination path name to disc.
   *
   * @param integer $chmod
   * @return FilebaseImage $destination
   */
  public function save($chmod = 0777)
  {
    if(!is_resource($this->destination_resource)) throw new FilebaseException('Nothing to save.');
    switch($this->destination->getMimeType())
    {
      case 'image/jpeg':
        imagejpeg($this->destination_resource, $this->destination->getPathname(), $this->destinationQuality);
        break;
      case 'image/gif':
        imagegif($this->destination_resource, $this->destination->getPathname());
        break;
      case 'image/png':
        imagepng($this->destination_resource, $this->destination->getPathname(), round($this->destinationQuality/10));
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
   * @param array $dimensions
   * @param true    $scale
   */
  public function resize(array $dimensions)
  {
    if(!is_resource($this->source_resource) || !$this->destination instanceof FilebaseImage) throw new FilebaseException('You must set a source and a destination image to resize.');
    
    $image_data = $this->gfxEditor->getScaledImageData($this->source, $dimensions);

    $width                = $image_data['orig_width'];
    $height               = $image_data['orig_height'];
    $new_width            = $image_data['new_width'];
    $new_height           = $image_data['new_height'];
    $mime                 = $image_data['mime'];
   
    switch ($mime)
    {
      case  'image/jpeg':
        $this->destination_resource = imagecreatetruecolor($new_width, $new_height);
        $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        break;
      case 'image/png':
        $this->destination_resource = imagecreatetruecolor($new_width, $new_height);
        $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        break;
      case 'image/gif':
        $this->destination_resource = imagecreate($new_width, $new_height);
        $this->funcs['imagecopyresampled']($this->destination_resource, $this->source_resource, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        break;
    }
    return true;
  }
}