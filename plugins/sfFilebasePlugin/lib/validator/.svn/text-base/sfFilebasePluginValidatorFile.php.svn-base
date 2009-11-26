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
 * sfFilebasePluginValidatorFile validates uploaded files, internally based
 * on sfFilebasePluginUploadedFile object, not on the raw file-array that you
 * can access through sfWebRequest::getFiles() or php internal $_FILES globals.
 *
 * This class is derived from the original sfValidatorFile, written by
 * Fabien Potencier.
 *
 * @see sfValidatorFile
 */
class sfFilebasePluginValidatorFile extends sfValidatorFile
{
  /**
   * The file upload manager as provided by sfFilebasePlugin
   * @var sfFilebasePluginUploadedFilesManager
   */
  protected $manager;

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * required:    true if the value is required, false otherwise (default to true)
   *  * trim:        true if the value must be trimmed, false otherwise (default to false)
   *  * empty_value: empty value when value is not required
   *
   * Available error codes:
   *
   *  * required
   *  * invalid
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   * @param sfFilebasePluginUploadedFilesManager $manager
   */
  public function __construct($options = array(), $messages = array(), sfFilebasePluginUploadedFilesManager $manager)
  {
    $this->manager = $manager;
    parent::__construct($options, $messages);
  }

  /**
   * Configures the current validator.
   *
   * The api for sfFilebasePluginValidatorFile is derived from sfValidatorFile.
   * So there are only little changes in handling, but the return value
   * is of type sfFilebaseUploadedFile, which have a few ways to deal with
   * uploaded files, move them, create thumbnails and so on...
   *
   * There are no mime guessers, the sfFilebasePluginUploadedFilesManager
   * deals with that when you call sfFilebasePlugin::getUploadedFiles().
   *
   * There is also no path to specify, you do that later directly by calling
   * sfFilebasePluginUploadedFile::moveUploadedFile
   *
   * As a compromise, you cannot specify your own fileclass anymore, beside
   * extending sfFilebaseUploadedFile and -manager.
   *
   * Available options:
   *
   *  * max_size:             The maximum file size
   *  * mime_types:           Allowed mime types array or category (available categories: web_images)
   *  * mime_type_guessers:   An array of mime type guesser PHP callables (must return the mime type or null)
   *  * mime_categories:      An array of mime type categories (web_images is defined by default)
   *  * path:                 The path where to save the file - as used by the sfValidatedFile class (optional)
   *  * validated_file_class: Name of the class that manages the cleaned uploaded file (optional)
   *  * allow_overwrite:       If set to true, existing files will be overwritten. Otherwise, an form field error will rise (optional)
   *                          This comes only in effect, if path is set (otherwise you'd to save the file manually)
   *  * filebase              Instance of filebase, needed if you want to save the file under another location than the
   *                          symfony default filebasePlugindDirectory (web/uploads) (optional)
   *
   * Available error codes:
   *
   *  * max_size
   *  * mime_types
   *  * partial
   *  * no_tmp_dir
   *  * cant_write
   *  * extension
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorFile
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    unset($this->options['mime_type_guessers']);
    $this->addOption('mime_categories', array(
      'web_images' => sfFilebasePluginUtil::$WEB_IMAGES)
    );
    $this->addOption('allow_overwrite', false);
    $this->setOption('validated_file_class', 'sfFilebasePluginUploadedFile');

    $this->addMessage('file_exists', 'Destinated file %file% already exists.');

    $filebase = $this->getManager()->getFilebase();
    // Calculate target path
    if(!$this->getOption('path'))
    {
      $this->setOption('path', $filebase->getPathname());
    }
    else
    {
      $this->setOption('path', $filebase->getFilebaseFile($path)->getPathname());
    }
  }

  /**
   * Cleans the input value.
   *
   * This method is also responsible for trimming the input value
   * and checking the required option.
   *
   * @throws sfValidatorError
   * @param  sfFilebasePluginUploadedFile || array $value The input value
   * @return sfFilebasePluginUploadedFile $uploaded_file
   */
  public function clean($value)
  {
    // Workaround for Admin generator, you don't know if a raw array has been passed.
    if(is_array($value))
    {
      if (!sfFilebasePluginUploadedFilesManager::isUploadedFile($value))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => 'File array describes no valid uploaded file.'));
      }
      $value = $this->manager->produceUploadedFile($value, $this->getOption('path'));
    }

    
    if(! $value instanceof sfFilebasePluginUploadedFile)
    {
      throw new sfValidatorError($this, 'invalid');
    }
    
    if($value->hasError())
    {
      if($value->isError(sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_FILE))
      {
        // required?
        if ($this->options['required'])
        {
          throw new sfValidatorError($this, 'required');
        }

        // @todo: check if $value should be returned
        return $this->getEmptyValue();
      }
    }
    return $this->doClean($value);
  }

  /**
   * This validator returns a sfFilebasePluginUploadedFile object.
   *
   * The input value must be an instance of sfFilebasePluginUploadedFile
   *
   * @see sfValidatorBase
   * @see sfFilebasePluginUploadedFile
   * @return sfFilebasePluginUploadedFile $uploaded_file
   */
  protected function doClean($value)
  {
    // Path name to save the file in
    $path_name = null;
    
    if($value->hasError())
    {
      switch ($value->getError())
      {
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_INI_SIZE:
          $max = sfFilebasePluginUtil::getMaxUploadFileSize();
          if ($this->getOption('max_size'))
          {
            $max = min($max, $this->getOption('max_size'));
          }
          throw new sfValidatorError($this, 'max_size', array('max_size' => $max, 'size' => (int) $value['size']));
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_FORM_SIZE:
          throw new sfValidatorError($this, 'max_size', array('max_size' => 0, 'size' => (int) $value['size']));
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_PARTIAL:
          throw new sfValidatorError($this, 'partial');
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_TMP_DIR:
          throw new sfValidatorError($this, 'no_tmp_dir');
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_CANT_WRITE:
          throw new sfValidatorError($this, 'cant_write');
        case sfFilebasePluginUploadedFile::UPLOAD_ERR_EXTENSION:
          throw new sfValidatorError($this, 'extension');
      }
    }

    // check file size
    if ($this->hasOption('max_size') && $this->getOption('max_size') < (int) $value->getSize())
    {
      throw new sfValidatorError($this, 'max_size', array('max_size' => $this->getOption('max_size'), 'size' => (int) $value->getSize()));
    }

    // Raw type the browser provided
    // @todo: Eventually check mime of tmp-file by Util as a fallback?
    $mimeType = $value->getType();

    // check mime type
    if ($this->hasOption('mime_types'))
    {
      $mimeTypes = is_array($this->getOption('mime_types')) ? $this->getOption('mime_types') : $this->getMimeTypesFromCategory($this->getOption('mime_types'));
      if (!in_array($mimeType, $mimeTypes))
      {
        throw new sfValidatorError($this, 'mime_types', array('mime_types' => $mimeTypes, 'mime_type' => $mimeType));
      }
    }
    $filebase = $this->manager->getFilebase();
    $path = $filebase->getFilebaseFile($this->getOption('path'));
    $path_name = $filebase->getFilebaseFile($path->getPathname() . '/' . $value->getOriginalName());
    if($path_name->fileExists())
    {
      if(!$this->getOption('allow_overwrite'))
      {
        throw new sfValidatorError($this, 'file_exists', array('file'=>$path_name->getPathname()));
      }
    }
    $class_name = $this->getOption('validated_file_class');
    if(is_string($class_name))
    {
      return ($class_name == 'sfFilebasePluginUploadedFile') ?
        $value  :
        // $originalName, $type, $tempName, $size, $path = null
        new $class_name($path_name->getFilename(), $mimeType, $value->getTmpName()->getPathname(), $value->getSize(), $path_name->getPath());
    }
    else
    {
      return array(
        'name'      => $value->getOriginalName(),
        'tmp_name'  => $value->getTempName(),
        'type'      => $value->getType(),
        'error'     => $value->getError(),
        'extension' => $value->getExtension()
      );
    }
  }

  /**
   * Returns the mime type of a file.
   *
   * This methods call each mime_type_guessers option callables to
   * guess the mime type.
   *
   * Proxy for sfFilebasePluginFile::getMimeType()
   * 
   * @see sfFilebasePluginFile::getMimeType()
   * @param  string $file      The absolute path of a file
   * @param  string $fallback  The default mime type to return if not guessable
   * @return string The mime type of the file (fallback is returned if not guessable)
   */
  protected function getMimeType($file, $fallback)
  {
    return $this->manager->getFilebase()->getFilebaseFile($file)->getMimeType($fallback);
  }

  /**
   * @see sfValidatorBase
   */
  protected function isEmpty($value)
  {
    // empty if the value is not an array
    // or if the value comes from PHP with an error of UPLOAD_ERR_NO_FILE
    if($value instanceof sfFilebasePluginUploadedFile)
    {
      return $value->isError(sfFilebasePluginUploadedFile::UPLOAD_ERR_NO_FILE);
    }
    else
    { 
      return sfFilebasePluginUploadedFilesManager::isUploadedFile($value);
    }
  }

  /**
   * Returns the file upload manager as provided by
   * sfFilebasePlugin. Retrieve the current filebase by
   * using $this->getManager()->getFilebase();
   * @return sfFilebasePluginUploadedFilesManager $manager
   */
  public function getManager()
  {
    return $this->manager;
  }
}
