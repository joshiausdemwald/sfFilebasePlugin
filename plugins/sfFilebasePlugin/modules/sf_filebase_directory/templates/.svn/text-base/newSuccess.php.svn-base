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
?>
<?php use_helper('I18N', 'Date') ?>
<?php include_partial('sf_filebase_directory/assets') ?>

<div id="sf_admin_container">
  <h2><?php echo __('New Sf filebase directory', array(), 'messages') ?></h2>

  <?php include_partial('sf_filebase_directory/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('sf_filebase_directory/form_header', array('sf_filebase_directory' => $sf_filebase_directory, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('sf_filebase_directory/form', array('sf_filebase_directory' => $sf_filebase_directory, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('sf_filebase_directory/form_footer', array('sf_filebase_directory' => $sf_filebase_directory, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
