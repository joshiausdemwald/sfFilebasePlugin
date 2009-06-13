<?php
/**
 * This file is part of the sfWidgetFormTreePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfWidgetFormTreeSelectPlugin
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */

/**
 * sfWidgetFormRadioTree renders a tree structure list of 
 * radio buttons
 *
 * @see        sfWidgetForm
 */
class sfWidgetFormRadioTree extends sfWidgetForm
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * choices:          An array of possible choices (required)
   *  *                   Choices may be a flat array (with levels) or multdim,
   *  *                   with children:
   *  *
   *  *                   $choices = array(
   *  *                     1=> array('label'=>'test', 'children'=>array(
   *  *                     2=> array('label'=>'test2', 'children'=> array(
   *  *                       3=> array('label'=>'test3'),
   *  *                       4=> array('label'=>'hans')
   *  *                     )),
   *  *                     5=> array('label'=>'wurst')
   *  *                     )),
   *  *                     6=>array('label'=>'letzter')
   *  *                   );
   *  *
   *  *                   $choices = array(
   *  *                     1=>array('level'=>0, 'label'=>"+++1"),
   *  *                     2=>array('level'=>0, 'label'=>"+++2"),
   *  *                     3=>array('level'=>1, 'label'=>"+++3"),
   *  *                     4=>array('level'=>2, 'label'=>"+++4"),
   *  *                     8=>array('level'=>3, 'label'=>"+++8"),
   *  *                     9=>array('level'=>3, 'label'=>"+++9"),
   *  *                     10=>array('level'=>3, 'label'=>"+++10"),
   *  *                     11=>array('level'=>4, 'label'=>"+++11"),
   *  *                     12=>array('level'=>4, 'label'=>"+++12"),
   *  *                     13=>array('level'=>3, 'label'=>"+++13"),
   *  *                     5=>array('level'=>2, 'label'=>"+++5"),
   *  *                     6=>array('level'=>1, 'label'=>"+++6"),
   *  *                     7=>array('level'=>0, 'label'=>"+++7"),
   *  *                   );
   *  *
   *  * multiple:         true if the select tag must allow multiple selections
   *  * renderer_class:   The class to use instead of the default ones
   *  * renderer_options: The options to pass to the renderer constructor
   *  * renderer:         A renderer widget (overrides the expanded and renderer_options options)
   *                      The choices option must be: new sfCallable($thisWidgetInstance, 'getChoices')
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('choices');
    $this->addOption('label_key', 'label');
    $this->addOption('value_key', false);
    $this->addOption('level_key', 'level');
    $this->addOption('children_key', 'children');
    $this->addOption('class', 'radio_tree');
    $this->addOption('label_separator', '&nbsp;');
    $this->addOption('separator', "\n");
    $this->addOption('formatter', array($this, 'formatter'));
    $this->addOption('template', '%group% %options%');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $choices = sfWidgetFormTree::normalizeChoices($this->getOption('choices'), $this->getOption('label_key'), $this->getOption('value_key'), $this->getOption('level_key'), $this->getOption('children_key'));
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }
    return $this->formatChoices($name, $value, $choices, $attributes);;
  }

  protected function formatChoices($name, $value, $choices, $attributes)
  {
    $inputs = array();
    if($choices !== null)
    {
      foreach($choices AS $key => $option)
      {
        if(!is_array($option)) throw new InvalidArgumentException(sprintf('Choice value must be an array, %s given.', gettype($option)));
        if(!isset($option[$this->getOption('label_key')])) throw new InvalidArgumentException(sprintf('Choice with key %s must have a label.', $key));

        $id_attr  = $this->generateId($name.'[]', self::escapeOnce($key));
        $val_attr = self::escapeOnce($key);
        
        $baseAttributes = array(
          'name'  => $name,
          'type'  => 'radio',
          'value' => $val_attr,
          'id'    => $id_attr
        );

        if (strval($key) == strval($value === false ? 0 : $value))
        {
          $baseAttributes['checked'] = 'checked';
        }
        
        $inputs[] = array(
          'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
          'label' => $this->renderContentTag('label', $option[$this->getOption('label_key')], array('for' => $id_attr)),
          'children' => isset($option['children']) ? $this->formatChoices($name, $value, $option['children'], $attributes) : null
        );
      }
    }
    return call_user_func($this->getOption('formatter'), $this, $inputs);
  }

  public function formatter($widget, $inputs)
  {
    $rows = array();
    
    foreach ($inputs as $input)
    {
      $content = $input['input'].$this->getOption('label_separator').$input['label'];
      isset($input['children'])  && $content .= "\n" . $input['children'];
      $rows[] = $this->renderContentTag(
        'li',
        $content
      );
    }
    return $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }

  public function __clone()
  {
    if ($this->getOption('choices') instanceof sfCallable)
    {
      $callable = $this->getOption('choices')->getCallable();
      $class = __CLASS__;
      if (is_array($callable) && $callable[0] instanceof $class)
      {
        $callable[0] = $this;
        $this->setOption('choices', new sfCallable($callable));
      }
    }
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/sfWidgetFormTreePlugin/css/treeselect.css'=>'all');
  }
}