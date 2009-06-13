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
 * sfValidatorTree validates a sfWidgetFormRadioTree
 *
 * @see        sfValidatorBase
 */
class sfValidatorTree extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * choices:  An array of expected values (required)
   *  * multiple: true if the select tag must allow multiple selections
   *  * restrict_select_below: If set to an id or an array of ids (content
   *  *                        of selected value attributes), then you cannot
   *  *                        select a node within this subtree. That can be
   *  *                        used for e.g. tree movement operations, where it
   *  *                        is not allowed to move a subtree within its own.
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('choices');
    $this->addOption('label_key', 'label');
    $this->addOption('value_key', false);
    $this->addOption('level_key', 'level');
    $this->addOption('children_key', 'children');
    $this->addOption('multiple', false);
    $this->addOption('restrict_select_below', false);
    $this->addMessage('restricted', 'Value may not be selected below this parent tree.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $choices = sfWidgetFormTree::normalizeChoices($this->getOption('choices'), $this->getOption('label_key'), $this->getOption('value_key'), $this->getOption('level_key'), $this->getOption('children_key'));

    $restrict_select_below = $this->getOption('restrict_select_below');
    if($restrict_select_below instanceof sfCallable)
    {
      $restrict_select_below = $restrict_select_below->call();
    }
    if($restrict_select_below!==false && !is_array($restrict_select_below))
    {
      $restrict_select_below = array($restrict_select_below);
    }
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }

    if ($this->getOption('multiple'))
    {
      if (!is_array($value))
      {
        $value = array($value);
      }

      foreach ($value as $v)
      {
        if (!self::inChoices($v, $choices))
        {
          throw new sfValidatorError($this, 'invalid', array('value' => $v));
        }
        if($restrict_select_below!==false)
        {
          if(self::valueIsParentOf($v, $restrict_select_below, $choices))
          {
            throw new sfValidatorError($this, 'restricted', array('value' => $v));
          }
        }
      }
    }
    else
    {
      if (!self::inChoices($value, $choices))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
      if($restrict_select_below!==false)
      {
        if(self::valueIsParentOf($value, $restrict_select_below, $choices))
        {
          throw new sfValidatorError($this, 'restricted', array('value' => $value));
        }
      }
    }

    return $value;
  }

  static protected function valueIsParentOf($value, $needles, $choices, $is_in_restricted_tree = false)
  {
    if(!is_array($needles))
      $needles = array($needles);

    foreach($choices AS $key => $choice)
    {
      if(!$is_in_restricted_tree)
      {
        foreach($needles AS $k=>$needle)
        {
          // Parent found
          if((string)$needle === (string)$key)
          {
            if((string)$value === (string)$key)
            {
              return true;
            }
            $is_in_restricted_tree = true;
            unset($needles[$k]);
            break;
          }
        }
        
        if(isset($choice['children']))
        {
          if(self::valueIsParentOf($value, $needles, $choice['children'], $is_in_restricted_tree))
          {
            return true;
          }
        }
        $is_in_restricted_tree = false;
      }

      if($is_in_restricted_tree === true)
      {
        if((string)$value === (string)$key)
          return true;
      }
    }
    return false;
  }

  /**
   * Checks if a value is part of given choices (see bug #4212)
   *
   * @param  mixed $value   The value to check
   * @param  array $choices The array of available choices
   *
   * @return Boolean
   */
  static protected function inChoices($value, array $choices = array())
  {
    foreach ($choices as $key => $choice)
    {
      if(isset($choice['children']))
      {
        if(self::inChoices($value, $choice['children']))
        {
          return true;
        }
      }

      if ((string)$key === (string)$value)
      {
        return true;
      }
    }
    return false;
  }
}

