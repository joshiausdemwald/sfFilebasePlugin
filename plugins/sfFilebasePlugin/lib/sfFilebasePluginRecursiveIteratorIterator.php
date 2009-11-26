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
 * Iterator for recursively traversing filesystem structure to render
 * tree views.
 *
 */
class sfFilebasePluginRecursiveIteratorIterator extends RecursiveIteratorIterator
{
  private $tokenBegin;
  private $tokenEnd;
  public function __construct(sfFilebasePluginRecursiveDirectoryIterator $iterator, $mode=sfFilebasePluginRecursiveIteratorIterator::SELF_FIRST, $flags=0, $tokenBegin = "", $tokenEnd = "")
  {
    $this->tokenBegin = $tokenBegin;
    $this->tokenEnd = $tokenEnd;
    parent::__construct($iterator, $mode, $flags);
  }
  
  public function beginChildren()
  {
    echo $this->tokenBegin;
  }
  
  public function endChildren()
  {
    echo $this->tokenEnd;
  }
}