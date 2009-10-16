<?php
/**
 * Description of File
 *
 * @author joshi
 */
class Doctrine_Template_File extends Doctrine_Template
{
  /**
   *  @var sfFilebasePlugin $filebase
   */
  protected $filebase;

  /**
   * Array of File options
   *
   * @var string
   */
  protected $_options = array(
    'name' =>  'pathname',
    'alias' =>  null,
    'expression' =>  false,
    'options' =>  array('notnull' => true),
    'filebaseDirectory' => null

  );

  /**
   * Creates the filebase by options
   */
  public function setUp()
  {
    if(!$dir = $this->getOption('filebaseDirectory', null))
    {
      throw new sfFilebasePluginException('You must specify a filebase directory to use this behaviour');
    }
    $this->filebase = sfFilebasePlugin::getInstance($dir);
    if(!$this->filebase->fileExists())
      throw new sfFilebasePluginException('The base directory does not exist.');
  }
  
  /**
   * Set table definition for Timestampable behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $name = $this->_options['name'];
    if ($this->_options['alias'])
    {
      $name .= ' as ' . $this->_options['alias'];
    }
    $this->hasColumn($name, 'string', 255, $this->_options['options']);
    $this->addListener(new Doctrine_Template_Listener_File($this->_options));
  }

  /**
   * Retrieves the file associated with a tuple
   * @return sfFilebasePluginFile $file
   */
  public function getFile()
  {
    return $this->filebase->getFilebaseFile($this->_invoker[$this->getOption('name')]);
  }

  /**
   * Sets the pathname for one tuple
   *
   * @param sfFilebasePluginFile | string $file
   * @return Doctrine_Template_File
   */
  public function setFile($file)
  {
    $file = $this->filebase->getFilebaseFile($file);
    $this->_invoker[$this->getOption('name')] = $file->getRelativePathFromFilebaseDirectory();
    return $this;
  }

  /**
   * set
   *
   * @param mixed $name
   * @param mixed $value
   * @return void
   */
  public function set($name, $value)
  {
    if($name == $this->getOption('name'))
    {
      $this->setFile($value);
    }
    else
    {
      $this->_invoker->set($name, $value);
    }
  }

  /**
   * set
   *
   * @param mixed $name
   * @param mixed $value
   * @return void
   */
  public function get($name)
  {
    if($name == $this->getOption('name'))
    {
      return $this->getFile();
    }
    return $this->_invoker->get($name);
  }
}