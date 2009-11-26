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
    'options' =>  array('notnull' => true),
    'filebase' => null,
    'checkExistence' => true
  );

  /**
   * Creates the filebase by options
   */
  public function setUp()
  {
    if(!$filebase_id = $this->getOption('filebase', null))
    {
      throw new sfFilebasePluginException('You must specify a filebase to use this behaviour');
    }
    $this->filebase = sfFilebasePlugin::getInstance($filebase_id);
    if(!$this->filebase->fileExists())
      throw new sfFilebasePluginException('The filebase directory does not exist.');
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
    if(strlen($this->_invoker[$this->getOption('name')])>0)
    {
      return $this->filebase->getFilebaseFile($this->_invoker[$this->getOption('name')]);
    }
    return null;
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
}