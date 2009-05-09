<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author joshi
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
   * Saves target image.
   * @return true if successfully saved
   */
  public function save($chmod = 0777);

  /**
   * Destroy the image and frees ram.
   */
  public function destroy();
}
