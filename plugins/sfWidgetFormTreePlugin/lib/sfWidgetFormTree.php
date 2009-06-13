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
 * sfWidgetFormTree renders a tree widget consisting of radio buttons
 * or checkboxes.
 *
 * @see        sfValidatorBase
 */
class sfWidgetFormTree extends sfWidgetForm
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
    $this->addOption('multiple', false);
    $this->addOption('expanded', false);
    $this->addOption('renderer_class', false);
    $this->addOption('renderer_options', array());
    $this->addOption('renderer', false);
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
    if ($this->getOption('multiple'))
    {
      if ('[]' != substr($name, -2))
      {
        $name .= '[]';
      }
    }
    return $this->getRenderer()->render($name, $value, $attributes, $errors);
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return $this->getRenderer()->getStylesheets();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return $this->getRenderer()->getJavaScripts();
  }

  public function getChoices()
  {
    $choices = $this->getOption('choices');
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }

    return $choices;
  }

  public function getRenderer()
  {
    if ($this->getOption('renderer'))
    {
      return $this->getOption('renderer');
    }

    if (!$class = $this->getOption('renderer_class'))
    {
      $type = $this->getOption('multiple') ? 'checkbox' : 'radio';
      $class = sprintf('sfWidgetForm%sTree', ucfirst($type));
    }
    return new $class(array_merge(array('value_key'=>$this->getOption('value_key'), 'label_key'=>$this->getOption('label_key'), 'level_key'=>$this->getOption('level_key'), 'children_key'=>$this->getOption('children_key'), 'choices' => new sfCallable(array($this, 'getChoices'))), $this->options['renderer_options']), $this->getAttributes());
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
   * Tries to normalize the choices, so that you are able to
   * give a flat array with level, an iterator or a multi-dim array.
   *
   * @param mixed $choices
   */
  public static function normalizeChoices($choices, $label_key, $value_key, $level_key, $children_key)
  {
    if($choices instanceof sfCallable)
      $choices = $choices->call();
    
    if($choices instanceof Traversable)
    {
      $c = array();
      foreach($choices AS $key => $choice)
      {
        $c[$key] = $choice;
      }
      $choices = $c;
    }

    if(is_array($choices))
    {
      if(count($choices)===0) return;

      // assuming flat array, convert to a recursive one
      $first = array_slice($choices, 0, 1);
      if(isset($first[0][$level_key]))
      {
        $items      = array();
        $prev_level = null;
        $prev_id    = null;
        
        $path = array();
        foreach($choices AS $k=>$choice)
        {
          $item = array(
            $label_key => (string)$choice[$label_key]
          );

          if($prev_level !== null && $prev_level < $choice[$level_key])
          {
            $path[] = '[\''.$prev_id.'\'][\'children\']';
          }
          elseif ($prev_level !== null && $prev_level > $choice[$level_key])
          {
            for($i=0; $i<$prev_level-$choice[$level_key]; $i++)
              array_pop($path);
          }
          
          $path_string = implode('', $path);
          if($value_key)
          {
            eval("\$items{$path_string}['$choice[$value_key]']=\$item;");
          }
          else
          {
            eval("\$items{$path_string}['$k']=\$item;");
          }

          $prev_level = $choice[$level_key];
          $prev_id    = $value_key ? $choice[$value_key] : $k;
        }
      }
      elseif($children_key !== 'children')
      {
        function recurse($choices, $children_key)
        {
          $new_c = array();
          foreach($choices AS $k=>$c)
          {
            $new_r = array();
            foreach($c AS $kr=>$r)
            {
              if((string)$kr === $children_key)
              {
                $new_r['children'] = recurse($c[$children_key], $children_key);
              }
              else
              {
                $new_r[$kr] = $r;
              }
            }
            $new_c[$k] = $new_r;
          }
          return $new_c;
        }
        $items = $recurse($choices);
      }
      else
      {
        $items = $choices;
      }
      return $items;
    }
  }
}
