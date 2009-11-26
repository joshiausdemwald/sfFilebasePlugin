<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin.adminArea
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */

/**
 * sf_filebase_file module configuration.
 *
 * @package    test
 * @subpackage sf_filebase_file
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 12474 2008-10-31 10:41:27Z fabien $
 */
class sf_filebase_fileGeneratorConfiguration extends BaseSf_filebase_fileGeneratorConfiguration
{
  /**
   * Gets a new form object.
   *
   * @param  mixed $object
   *
   * @return sfForm
   */
  public function getForm($object = null)
  {
    $class = $this->getFormClass();

    $form = new $class(sfConfig::get('sf_public_filebase'), $object, $this->getFormOptions());

    $this->fixFormFields($form);

    return $form;
  }
}
