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
 * This class proviceds structs which can be used as a "rich" array.
 *
 */
class sfFilebasePluginStruct implements ArrayAccess, IteratorAggregate
{
  /**
   * Store-Array. All Data assigned to this store will
   * be put in that data-structure.
   *
   * @var array
   */
  protected $store = array();

  /**
   *
   * @var string
   */
  protected $class_name = 'sfFilebasePluginStruct';
  
  /**
   * Sets the class name for sub-structs
   *
   * @param string $class_name
   */
  public function setStructClass($class_name = 'sfFilebasePluginStruct')
  {
    $this->class_name = $class_name;
  }

  /**
   * Constructor. Takes an array with store-values
   * as an optional argument.
   * 
   * @param array $stdval
   */
  public function __construct(array $stdval = array())
  {
    $this->setStore($stdval);
  }

  /**
   * Sets the variable store of this struct.
   * Converts all nested arrays into a Struct,
   * leaving instances of Traversable untouchted.
   *
   * @param array $store
   */
  public function setStore(array $store)
  {
    foreach($store AS $i=>$item)
    {
      $this->__set($i, $item);
    }
  }

  /**
   * Returns this store as an instance of
   * Array Iterator. Wrapper for getIterator()
   *
   * @see Store::getIterator()
   * @return ArrayIterator $iter
   */
  public function getStore()
  {
    return $this->getIterator();
  }

  /**
   *
   * @param string $name
   * @return mixed
   */
  public function __get($name)
  {
    return $this->offsetExists($name) ? $this->store[$name] : null;
  }

  /**
   * Calculates the substring of a getter/setter
   * method. E.g. getHanswurst() => hanswurst
   *
   * @param string $namete
   * @return string
   */
  private function getNameFromGetterSetter($name)
  {
    $name = substr($name, 3);
    $name{0} = strtolower($name{0});
    return $name;
  }

  /**
   * Provides data access by using standard getter/setter-
   * syntax. E.g.:
   *
   * @example $this->setVarname(value) sets "varname" to "value"
   * @example $this->getVarname() returns valueof "varname", error if not set
   * @example $this->getVarname(std_value) returns value of "varname" or std_value if not set
   * @param string $name
   * @param array $args
   * @throws LogicException
   * @return mixed
   */
  public function __call($name, $args)
  {
    if(strpos($name, 'get')===0)
    {
      $name = $this->getNameFromGetterSetter($name);
      if(empty($name))
        throw new LogicException("Variable initialization error: Getter has no name.");
      else
        return isset($args[0]) && !$this->__offsetExists($name) ?
                                                        $args[0] :
                                              $this->__get($name);
    }
    elseif(strpos($name, 'set')===0)
    {
      $name = $this->getNameFromGetterSetter($name);
      if(empty($name))
      {
        throw new LogicException("Variable initialization error: Setter has no name.");
      }
      else
      {
        if(isset($args[0]))
        {
          $this->__set($name, $args[0]);
          return;
        }
        else
          throw new LogicException(sprintf("Variable initialization error of var \"%s\": no value assigned.", $name));
      }
    }
    throw new Exception(sprintf("Method \"%s\" not found.", $name));
  }

  /**
   *
   * @param mixed string|int $key
   * @param mixed $default
   * @return mixed
   */
  public function getAttribute($key, $default = null)
  {
    return $this->offsetExists($key) ? $this->__get($key) : $default;
  }

  /**
   *
   * @param mixed string|int  $key
   * @param mixed $value
   */
  public function setAttribute($key, $value)
  {
    $this->__set($key, $value);
  }

  /**
   * Adds a new Value to this struct. Converts all
   * nested arrays into structs.
   *
   * @param string $name
   * @param mixed $value
   */
  public function __set($name, $value)
  {
    $this->store[$name] = is_array($value) ? 
      new $this->class_name($value) :
      $value;
  }

  /**
   *
   * @param string $offset
   */
  public function offsetUnset($offset)
  {
    unset($this->store[$offset]);
  }

  /**
   *
   * @param string $offset
   * @return boolean
   */
  public function offsetExists($offset)
  {
    return array_key_exists($offset, $this->store);
  }

  /**
   *
   * @param string $offset
   * @return mixed
   */
  public function offsetGet($offset)
  {
    return $this->__get($offset);
  }

  /**
   *
   * @param string $offset
   * @param mixed $value
   */
  public function offsetSet($offset, $value)
  {
    $this->__set($offset, $value);
  }

  /**
   * Returns Array Iterator over struct data
   *
   * @return ArrayIterator $iter
   */
  public function getIterator()
  {
    return new ArrayIterator($this->store);
  }
}