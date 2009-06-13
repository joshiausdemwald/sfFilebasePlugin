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
 * sfValidatorTreeMany validates a sfWidgetFormCheckboxTree
 *
 * @see        sfValidatorTree
 */
class sfValidatorTreeMany extends sfValidatorTree
{
  /**
   * Configures the current validator.
   *
   * @see sfValidatorChoice
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('multiple', true);
  }
}
