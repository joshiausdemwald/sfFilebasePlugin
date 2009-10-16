<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This is the cache representation of a filebase.
 * Each file that is stored temporary will be placed in its cache directory.
 * Additionally the cache provides some index structures for faster file access.
 *
 * @author joshi
 */
class sfFilebasePluginCache
{
  /**
   * @var sfFilebasePluginDirectory
   */
  protected $cacheDirectory;

  /**
   * @var sfFilebasePluginFile
   */
  protected $indexFile;

  /**
   *
   * @var sfFilebasePlugin $filebase;
   */
  protected $filebase;

  /**
   * Constructs a filebase cache
   * @param sfFilebasePlugin $filebase
   */
  public function __construct(sfFilebasePlugin $filebase, $cache_directory = null)
  {
    $this->filebase = $filebase;
    if($cache_directory  === null)
    {
      $cache_directory = $this->filebase->getPathname() . '/.' . md5(get_class($this));
    }

    // CREATE THE CACHE DIRECTORY IF NEEDED
    $this->initCacheDirectory($cache_directory);
  }

  /**
   * Creates the cache directory on demand
   *
   * @param sfFilebasePluginDirectory | string: The pathname of the cache directory.
   * @return sfFilebasePluginDirectory $cache_directory
   */
  public function initCacheDirectory($cache_directory)
  {
    $this->cacheDirectory = $this->filebase->getFilebaseFile($cache_directory);

    if(!$this->cacheDirectory->fileExists())
    {
      $this->cacheDirectory = $this->filebase->mkDir($this->cacheDirectory);
    }
    return $this->cacheDirectory;
  }

  /**
   * Returns the cache directory
   * @return sfFilebasePluginCache
   */
  public function getCacheDirectory()
  {
    return $this->cacheDirectory;
  }

  /**
   * Clears the cache
   * @return boolean true if clearing was successfull
   */
  public function clear()
  {
    try
    {
      foreach($this->getCacheDirectory() AS $file)
      {
        $file->delete(true);
      }
      return true;
    }
    catch (Exception $e)
    {
      throw $e;
    }
    return false;
  }

  /**
   * Adds a file to the cache.
   * @param sfFilebasePluginFile
   * @return sfFilebasePluginFile: The cached file
   */
  public function addFile($file)
  {
    return $file->copy($this->getCacheDirectory() . '/' . $file->getHash(), true);
  }
}