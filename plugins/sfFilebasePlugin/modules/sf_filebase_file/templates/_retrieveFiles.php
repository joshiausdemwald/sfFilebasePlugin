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
<div class="sf_admin_form_row">
  <?php if($file instanceof sfFilebasePluginFile && $file->fileExists()):?>
    <?php if($is_edit):?>
      <h3><?php echo $file_object->getFilename()?></h3>
    <?php endif?>
    <ul class="stats">
      <?php if($file instanceof sfFilebasePluginImage):?>
        <li>
          <a href="<?php echo url_for('sf_filebase_display_image', array('width'=>'600', 'file'=>$file_object->getId()))?>">
            <img src="<?php echo url_for('sf_filebase_display_image', array('height'=>120, 'file'=>$file_object->getId()))?>" alt="<?php echo $file_object->getFilename()?>" /></a></li>
      <?php endif?>
        <li><span><?php echo __('Type')?>:</span> <?php echo $file->getMimeType()?></li>
        <li>
            <span><?php echo __('Download')?>: </span>
            <?php echo link_to($file_object->getFilename(), 'sf_filebase_download_file', array('file'=>$file_object->getId()));?></li>
    </ul>
  <?php else:?>
    <p>Error: File does not exist</p>
  <?php endif?>
 </div>
    