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
 * sfFilebasePluginFileObject enriches splFileObject
 *
 * @see        SplFileObject
 */
class sfFilebasePluginFileObject extends splFileObject
{
  /**
   * @var sfFilebasePlugin $filebase
   */
  protected $filebase;

  /**
   *
   * @var sfFilebasePluginFile $file;
   */
  protected $file;
  
  /**
   * Delegates method calls to the underlying
   * sfFilebasePluginFile-Instance.
   * 
   * @param string $method
   * @param array $args 
   */
  public function __call($method_name, $args)
  {
    if(method_exists($this->file, $method_name))
      return $this->file->$method_name(implode(',',$args));
    throw new RuntimeException('Method not found.');
  }

	/**
	 * Constructs a new file object
	 *
	 * @param sfFilebasePluginFile $file_name         The name of the stream to open
   * @param sfFilebasePlugin $filebase
   * @param $open_mode         The file open mode
	 * @param $use_include_path  Whether to search in include paths
	 * @param $context           A stream context
   * @throw RuntimeException   If file cannot be opened (e.g. insufficient
	 *                           access rights).
	 */
	function __construct(sfFilebasePluginFile $file_name, sfFilebasePlugin $filebase, $open_mode = 'r', $use_include_path = false, $context = null)
	{
    is_resource($context) ?
      parent::__construct($file_name->getPathname(), $open_mode, $use_include_path, $context) :
      parent::__construct($file_name->getPathname(), $open_mode, $use_include_path);
    $this->filebase = $filebase;
    $this->file     = $file_name;
    $this->setFileClass('sfFilebasePluginFileObject');
    $this->setInfoClass('sfFilebasePluginFile');
	}

  /**
   * Returns the underlying sfFilebasePluginInfo
   * instance.
   * 
   * @return sfFilebasePluginFile $file
   */
  public function getFilebaseFile()
  {
    return $this->file;
  }
  
	/**
   * Wraps native function file_get_contents()
   * 
   * @param flags int[optional] <p>
   * For all versions prior to PHP 6, this parameter is called
   * use_include_path and is a bool.
   * The flags parameter is only available since
   * PHP 6. If you use an older version and want to search for
   * filename in the
   * include path, this
   * parameter must be true. Since PHP 6, you have to use the
   * FILE_USE_INCLUDE_PATH flag instead.
   * </p>
   * <p>
   * The value of flags can be any combination of
   * the following flags (with some restrictions), joined with the binary OR
   * (|) operator.
   * </p>
   * <p>
   * <table>
   * Available flags
   * <tr valign="top">
   * <td>Flag</td>
   * <td>Description</td>
   * </tr>
   * <tr valign="top">
   * <td>
   * FILE_USE_INCLUDE_PATH
   * </td>
   * <td>
   * Search for filename in the include directory.
   * See include_path for more
   * information.
   * </td>
   * </tr>
   * <tr valign="top">
   * <td>
   * FILE_TEXT
   * </td>
   * <td>
   * If unicode semantics are enabled, the default encoding of the read
   * data is UTF-8. You can specify a different encoding by creating a
   * custom context or by changing the default using
   * stream_default_encoding. This flag cannot be
   * used with FILE_BINARY.
   * </td>
   * </tr>
   * <tr valign="top">
   * <td>
   * FILE_BINARY
   * </td>
   * <td>
   * With this flag, the file is read in binary mode. This is the default
   * setting and cannot be used with FILE_TEXT.
   * </td>
   * </tr>
   * </table>
   * </p>
   * @param context resource[optional] <p>
   * A valid context resource created with
   * stream_context_create. If you don't need to use a
   * custom context, you can skip this parameter by &null;.
   * </p>
   * @param offset int[optional] <p>
   * The offset where the reading starts.
   * </p>
   * @param maxlen int[optional] <p>
   * Maximum length of data read.
   * </p>
   * @return string The function returns the read data or false on failure.
   * @throws sfFilebasePluginException
   * </p>
   */
  public function fileGetContents($flags = null, $context=null, $offset=null, $maxlen=null)
  {
    $ret = '';
    if($context !== null && $offset !== null && $maxlen !== null)
    {
      $ret = file_get_contents($this->getPathname(), $flags, $context, $offset, $maxlen);
    }
    elseif($context!== null && $offset !== null)
    {
      $ret = file_get_contents($this->getPathname(), $flags, $context, $offset);
    }
    elseif($context !== null)
    {
      $ret = file_get_contents($this->getPathname(), $flags, $context);
    }
    elseif($flags!==null)
    {
      $ret = file_get_contents($this->getPathname(), $flags);
    }
    else
    {
      $ret = file_get_contents($this->getPathname());
    }
    if($ret === false) throw new sfFilebasePluginException(sprintf('Error reading file %s: %s', $this->getPathname(), implode('\n', error_get_last())));
    return $ret;
  }

  /**
   * Writes $length bytes of $str.
   *
   * @param string $str
   * @param integer $length
   * @return boolean
   */
  public function write($str, $length = null)
  {
    return $length === null ?
      $this->fwrite($str):
      $this->fwrite($str, $length);
  }

  /**
   * Writes a portion of string ending
   * by a new line delimiter with maxlength =
   * $length bytes.
   *
   * @param string $str
   * @return boolean
   */
  public function writeLn($str, $nl_delim = "\n", $length = null)
  {
    return $length === null ?
      $this->fwrite($str . $nl_delim) :
      $this->fwrite($str . $nl_delim, $length);
  }
}