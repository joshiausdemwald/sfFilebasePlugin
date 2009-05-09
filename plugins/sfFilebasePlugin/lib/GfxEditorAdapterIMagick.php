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
class GfxEditorAdapterIMagick implements IGfxEditorAdapter
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
  public function setSource(FilebaseImage $source)
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

   * @param FilebaseImage $destination
   */
  public function setDestination(FilebaseImage $destination)
  {
    $this->destination = $destination;
    $this->destination_resource = $this->source_resource->clone();
  }

  /**
   * Saves the image as destination path name to disc.
   *
   * @param integer $chmod
   * @return FilebaseImage $destination
   */
  public function save($chmod = 0777)
  {
    if(!$this->destination_resource instanceof Imagick) throw new FilebaseException('Nothing to save.');
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
   * @param array $dimensions
   * @param true    $scale
   */
  public function resize(array $dimensions)
  {
    if(!$this->source_resource instanceof Imagick || !$this->destination instanceof FilebaseImage) throw new FilebaseException('You must set a source and a destination image to resize.');

    $image_data = $this->gfxEditor->getScaledImageData($this->source, $dimensions);

    $width                = $image_data['orig_width'];
    $height               = $image_data['orig_height'];
    $new_width            = $image_data['new_width'];
    $new_height           = $image_data['new_height'];
    $mime                 = $image_data['mime'];

    $this->destination_resource->thumbnailImage($new_width, $new_height);
    return true;
  }
}