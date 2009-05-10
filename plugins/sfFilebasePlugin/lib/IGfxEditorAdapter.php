<?php
/**
 * This file is part of the sfFilebase symfony plugin.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Interface for implementing new GfxEditorAdapters.
 *
 * @todo       Implement improved image editing capabilities.
 * @package    de.optimusprime.sfFilebasePlugin
 * @author     Johannes Heinen <johannes.heinen@gmail.com>
 * @copyright  Johannes Heinen <johannes.heinen@gmail.com>
 */
interface IGfxEditorAdapter
{
  /**
   * "fake" Constructor
   * @see GfxEditor::__construct()
   * @param GfxEditor $editor
   */
  function initialize(GfxEditor $editor);

  /**
   * @return boolean
   */
  public function isSupported();

  /**
   * @param FilebaseImage $image
   */
  public function setSource(FilebaseImage $source);

  /**
   * Sets destination pathname. Target may be the same
   * as source
   * @param FilebaseImage $image
   */
  public function setDestination(FilebaseImage $destination);

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
   * @return FilebaseImage $image: THe rotated image
   */
  public function rotate($deg, $bgcolor);
  
  /**
   * Saves target image.
   * @return true if successfully saved
   */
  public function save($chmod = 0777);

  /**
   * Destroy the image and frees ram.
   */
  public function destroy();
}
