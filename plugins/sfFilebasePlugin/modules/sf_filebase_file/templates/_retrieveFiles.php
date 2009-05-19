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
 */?><div class="sf_admin_form_row">
  <?php if($is_edit):?>
  <h3><?php echo $file->getRelativePathFromFilebaseDirectory()?></h3>
  <?php endif?>
  <ul>
    <?php if($file instanceof sfFilebasePluginImage):?>
      <li><img src="<?php echo url_for('sf_filebase_display_image', array('height'=>120, 'file'=>$file->getHash()))?>" alt="<?php echo $file->getFilename()?>"/></li>
      <li><?php echo __('Download')?><br/><?php echo link_to($file->getFilename(), 'sf_filebase_download_file', array('file'=>$file->getHash()));?></li>
    <?php else:?>
      <li><?php echo __('Download')?>: <br/><?php echo link_to($file->getFilename(), 'sf_filebase_download_file', array('file'=>$file->getHash()));?></li>
    <?php endif?>
  <ul>
 </div>
    