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
  <h2><?php echo __('Sf filebase directory List', array(), 'messages') ?></h2>

  <?php include_partial('sf_filebase_directory/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('sf_filebase_directory/list_header', array('pager' => $pager)) ?>
  </div>

  <div id="sf_admin_bar">
    <?php include_partial('sf_filebase_directory/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <form action="<?php echo url_for('sf_filebase_directory_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('sf_filebase_directory/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('sf_filebase_directory/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('sf_filebase_directory/list_actions', array('helper' => $helper)) ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('sf_filebase_directory/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
