<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleManager
 *
 * @package cms
 * @author joshi
 */
class ModuleManager extends EventSubject implements ArrayAccess, IEventObserver
{
  /**
   *  Iterator over ModuleManager instances
   *
   * @var array ModuleManager $instances
   */
  private static $instances = array();

  /**
   * sfContext Implementation
   *
   * @var sfContext $context
   */
  private $context;

  /**
   *
   * @var string $name
   */
  private $name;

  /**
   * Array of Modules
   *
   * @var array $modules
   */
  private $modules = array();

  /**
   * Path to module directory
   *
   * @var string
   */
  private $module_path;

  /**
   * Private constructor
   *
   * @param string $name
   */
  private function __construct($context, $name)
  {
    $this->context = $context;
    $this->name = $name;

    // Store reference in context
    try
    {
      $module_managers = $context->get('cms_module_managers');
    }
    catch(sfException $e)
    {
      $module_managers = new Struct();
    }
    $module_managers[$this->name] = $this;
    $this->context->set('cms_module_managers', $module_managers);
  }

  /**
   * Returns a module manager instance
   *
   * @static
   * @param string $name
   * @return ModuleManager $manager
   */
  public static function instance(sfContext $context, $name = 'default')
  {
    ! isset(self::$instances[$name]) && self::$instances[$name] = new ModuleManager($context, $name);
    return self::$instances[$name];
  }

  /**
   * Returns ModuleManager's name
   * 
   * @return string $name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Returns application contexts as an instance of sfContext.
   *
   * @return sfContext $context
   */
  public function getContext()
  {
    return $this->context;
  }

  /**
   * Loads all modules on the given path.
   *
   * @param string $module_path
   */
  public function load($module_path)
  {
    $this->module_path = $module_path;
    foreach(new DirectoryIterator($module_path) AS $file)
    {
      if($file->isDir() && ! $file->isDot())
      {
        $this->addModule(new Module($this->context, new Struct(sfYamlConfigHandler::parseYaml($file->getPathname().'/config.yml'))));
      }
    }
  }

  /**
   * Adds a Module.
   *
   * @param Module $module
   */
  public function addModule(Module $module)
  {
    $this[$module->getName()] = $module;
  }

  /**
   * Checks all Module dependancies and combinates
   * desired Modules as observers/subjects to each
   * other.
   *
   * @throws ModuleException
   */
  public function checkDependancies()
  {
    // Check all modules
    foreach($this->modules AS $module)
    {
      // Check dependancies
      foreach($module->getDependancies() AS $dept)
      {
        $dept_module = $this->getModule($dept);
        if(! $dept_module instanceof Module)
        throw new ModuleException(sprintf('Required module %s is not available', $dept));
      }

      // Assign events
      foreach($module->getEventListeners() AS $module_name => $listeners)
      {
        $source_module = $this->getModule($module_name);

        // Module installed?
        if($source_module !== null)
        {
          // Traverse event-callback-map and attach observers
          foreach($listeners AS $event_name)
          {
            $source_module->addObserver($module, $event_name);
          }
        }
      }
    }
  }

  /**
   * Checks if a module of the specified name
   * exists.
   *
   * @param string $name
   */
  public function hasModule($name)
  {
    return isset($this[$name]);
  }

  /**
   * Returns an assigned module by its name
   *
   * @param string $name
   * @return Module $module
   */
  public function getModule($name)
  {
    return $this[$name];
  }

  /**
   * ArrayAccess Implementation.
   *
   * @param string $name
   * @return boolean
   */
  public function offsetExists($name)
  {
    return isset($this->modules[$name]) ? true : false;
  }

  /**
   * ArrayAccess implementation
   *
   * @param string $name
   * @param Module $module
   */
  public function offsetSet($name, $module)
  {
    if(!$module instanceof Module)
        throw new ModuleException(sprintf('Error adding Module to %s "%s": "%s" must be an instance of class "Module".', __CLASS__, $this->getName(), $name));
    if(isset($this[$name]))
      throw new ModuleException(sprintf('Error adding Module to %s "%s": "%s" does already exist. Try unsetting it before adding a new module.', __CLASS__, $this->getName(), $name));
      
    $this->modules[$name] = $module;
    $module->addObserver($this);
  }

  /**
   * ArrayAccess implementation
   *
   * @param string $name
   */
  public function offsetUnset($name)
  {
    if(!isset($this[$name]))
      throw new ModuleException(sprintf('Module "%s" does not exist in this instance of %s "%s" and so it cannot be unset.', $name, __CLASS__, $this->getName()));

    //$this->removeObserver($this[$name]);
    unset($this->modules[$name]);

  }

  /**
   * ArrayAccess implementation
   *
   * @param string $name
   * @return Module
   */
  public function offsetGet($name)
  {
    if(!isset($this[$name]))
      throw new ModuleException(sprintf('Module "%s" does not exist in this instance of %s "%s".', $name, __CLASS__, $this->getName()));
    return $this->modules[$name];
  }

  /**
   * Implements IEventObserver. Listens to all Events.
   *
   * @param Event $event
   */
  public function notify(Event $event)
  {
    
  }
}