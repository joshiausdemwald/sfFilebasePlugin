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
 * @see        sfWidgetFormTree
 */
class sfWidgetFormTreeMultiple extends sfWidgetFormTree
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->setOption('multiple', true);
  }
}