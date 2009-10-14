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
 * Interface for implementing new sfFilebasePluginGfxEditorAdapters.
 *
 * @todo       Implement improved image editing capabilities.
 */
interface sfFilebasePluginGfxEditorAdapterInterface
{
  /**
   * "fake" Constructor
   * @see sfFilebasePluginGfxEditor::__construct()
   * @param sfFilebasePluginGfxEditor $editor
   */
  function initialize(sfFilebasePluginGfxEditor $editor);

  /**
   * @return boolean
   */
  public function isSupported();

  /**
   * @param sfFilebasePluginImage $image
   */
  public function setSource(sfFilebasePluginImage $source);

  /**
   * Sets destination pathname. Target may be the same
   * as source
   * @param sfFilebasePluginImage $image
   */
  public function setDestination(sfFilebasePluginImage $destination);

  /**
   * Resizes an image.  If scale set to true, image
   * may be resized greater than its original size.
   * 
   * @param array $dimensions
   * @return true if success
   */
  public function resize(array $dimensions);


  /**
   * Rotates an image to $deg degree
   * 
   * @param integer $deg: The amount to rotate
   * @param string $bgcolor: The background color in html hexadecimal notation
   * @return sfFilebasePluginImage $image: THe rotated image
   */
  public function rotate($deg, $bgcolor);
  
  /**
   * Saves target image.
   * @return true if successfully saved
   */
  public function save($chmod = 0777);
  
  /**
   * Sets the flag that determins if the processor should preserve transparency
   * during the image manipulation.
   * 
   * @param boolean $preserve_transparency 
   */
  public function setPreserveTransparency($preserve_transparency);

  /**
   * Destroy the image and frees ram.
   */
  public function destroy();
}
