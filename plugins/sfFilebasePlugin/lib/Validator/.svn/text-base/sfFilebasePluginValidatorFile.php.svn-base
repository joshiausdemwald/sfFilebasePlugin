<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfFilebasePluginValidatorFile
 *
 * @author joshi
 */
class sfFilebasePluginValidatorFile extends sfValidatorBase
{
   /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max_size:             The maximum file size
   *  * mime_types:           Allowed mime types array or category (available categories: web_images)
   *  * mime_type_guessers:   An array of mime type guesser PHP callables (must return the mime type or null)
   *  * mime_categories:      An array of mime type categories (web_images is defined by default)
   *  * path:                 The path where to save the file - as used by the sfValidatedFile class (optional)
   *  * validated_file_class: Name of the class that manages the cleaned uploaded file (optional)
   *
   * There are 3 built-in mime type guessers:
   *
   *  * guessFromFileinfo:        Uses the finfo_open() function (from the Fileinfo PECL extension)
   *  * guessFromMimeContentType: Uses the mime_content_type() function (deprecated)
   *  * guessFromFileBinary:      Uses the file binary (only works on *nix system)
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
    $this->addOption('max_size');
    $this->addOption('mime_types');
    $this->addOption('mime_categories', array(
      'web_images' => array(
      'image/jpeg',
      'image/pjpeg',
      'image/png',
      'image/x-png',
      'image/gif',
    )));
    $this->addMessage('max_size', 'File is too large (maximum is %max_size% bytes).');
    $this->addMessage('mime_types', 'Invalid mime type (%mime_type%).');
    $this->addMessage('partial', 'The uploaded file was only partially uploaded.');
    $this->addMessage('no_tmp_dir', 'Missing a temporary folder.');
    $this->addMessage('cant_write', 'Failed to write file to disk.');
    $this->addMessage('extension', 'File upload stopped by extension.');
  }

   /**
   * Cleans the input value.
   *
   * This method is also responsible for trimming the input value
   * and checking the required option.
   *
   * @throws sfValidatorError
   * @param  sfFilebasePluginUploadedFile $value  The input value
   * @return sfFilebasePluginUploadedFile $uploaded_file
   */
  public function clean($value)
  {
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
    switch ($value->getError())
    {
      case sfFilebasePluginUploadedFile::UPLOAD_ERR_INI_SIZE:
        $max = ini_get('upload_max_filesize');
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

    // check file size
    if ($this->hasOption('max_size') && $this->getOption('max_size') < (int) $value->getSize())
    {
      throw new sfValidatorError($this, 'max_size', array('max_size' => $this->getOption('max_size'), 'size' => (int) $value->getSize()));
    }

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
    return $value;
  }
}
