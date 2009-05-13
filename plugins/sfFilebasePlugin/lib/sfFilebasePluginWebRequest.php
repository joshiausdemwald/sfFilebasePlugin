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
 * This class extends sfWebRequest to add a convinience
 * method for accessing uploaded files, converted by
 * sfFilebasePlugin into instances of sfFilebasePluginUploadedFile.
 *
 * @package de.optimusprime.sfFilebasePlugin
 * @see     sfWebRequest
 */
class sfFilebasePluginWebRequest extends sfWebRequest
{
  /**
   * Returns uploaded files as an array consisting of instances of
   * sfFilebasePluginUploadedFile.
   *
   * This can be useful when you are using sfFilebasePluginValidatorFile
   * and want to bind the instances to a sfForm:
   *
   * * $my_form->bind(
   * *   $request->getParameter('foo'),
   * *   $request->getUploadedFiles('foo')
   * * );
   *
   * @param  string $name: The prefix if set, e.g.
   *         <input type="file" name="hanswurst[file]/>
   *         access by $request->getUploadedFilese('hanswurst')
   * @return array sfFilebasePluginUploadedFile $files
   */
  public function getUploadedFiles($name = null)
  {
    return sfFilebasePlugin::getInstance()->getUploadedFiles($name);
  }
}