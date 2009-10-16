<?php
/**
 *
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
<?php use_helper('I18N')?>
<?php use_helper('Text')?>
<div id="Sf_Filebase_Tree">
  <h3><?php echo __('Files')?></h3>
  <?php include_component('sf_filebase_filedeliverer', 'directoryTree');?>
</div>
<div id="Sf_Filebase_Tagcloud">
  <h3>
  <?php echo __('Tags')?></h3>
  <?php if($tag):?>
    <p class="tagfilter"><?php echo __('Tagfilter:')?> <?php echo $tag?> (<?php echo link_to(__('reset'), 'sf_filebase_gallery', array('id'=>$parent->getId(), 'tag'=>'___'))?>)</p>
  <?php endif?>
  <?php include_component('sf_filebase_gallery', 'tagcloud', array('url' => url_for('sf_filebase_gallery', array('id' => $parent->getId(), 'tag' => '%tag%'))));?>

  <ul id="Sf_Filebase_Navi">
    <li><?php echo link_to(__('Upload new Files'), 'sf_filebase_file_new')?></li>
    <li><?php echo link_to(__('Create a new directory'), 'sf_filebase_directory_new')?></li>
  </ul>

  <!--form method="post" accept-charset="utf-8" enctype="x-www/urlencoded" action="<?php echo $_SERVER['REQUEST_URI']?>">
    <div><?php echo $filter?></div>
    <div><input type="submit" name="submit" value="<?php echo __('submit')?>" />
  </form-->
</div>
<div id="Sf_Filebase_Gallery_Container">
  <h3><?php echo __('Gallery')?></h3>
  <?php include_component('sf_filebase_gallery', 'breadcrumb', array('node'=>$parent));?>
  <ul id="Sf_Filebase_Gallery">
    <?php if($parent->getNode()->hasParent()):?>
      <li class="file file-parent">
        <div class="contents">
          <p>
            <a href="<?php echo url_for('sf_filebase_gallery', array('id'=>$parent->getNode()->getParent()->getId()))?>" class="toggle-row file-title file-title-directory"><span>../</span></a>
          </p>
        </div>
      </li>
    <?php endif?>
    <?php if($parent->getNode()->hasChildren()):?>
      <?php foreach($parent->getNode()->getChildren() AS $node):?>
        <?php if($node instanceof sfFilebaseDirectory):?>
          <li class="file file-directory">
            <div class="contents">
              <a href="<?php echo url_for('sf_filebase_gallery', array('id'=>$node->getId()))?>" class="toggle-row file-title file-title-directory"><span><?php echo $node->getFilename()?></span></a>
              <span class="file-directory-controls">
                <?php echo link_to('<span>'.__('Edit').'</span>', 'sf_filebase_directory_edit', array('id'=>$node->getId()), array('class'=>'directory-edit-link') )?>
                <?php echo link_to('<span>'.__('Delete').'</span>', '@sf_filebase_directory_delete?id='.$node->getId(), array('class'=>'directory-delete-link', 'method'=>'delete', 'confirm'=>__('Do you really want to delete the whole directory and its contents?')))?>
              </span>
            </div>
          </li>
        <?php else:?>
          <?php if(!$tag || $node->hasTag($tag)):?>
            <?php $file = sfConfig::get('sf_public_filebase')->getFilebaseFile($node->getHash())?>
            <li class="file<?php $file instanceof sfFilebaseImage && print ' file-image'?>">
              <div class="contents">
                <?php #if($file->isImage()):?>
                  <?php #$url = url_for('@sf_filebase_display_image?width=580&file='.$node->getId())?>
                <?php #else:?>
                  <?php $url = url_for('@sf_filebase_download_file?file='.$node->getId())?>
                <?php #endif?>
                <a href="<?php echo $url?>">
                  <?php if($file instanceof sfFilebasePluginImage):?>
                    <img src="<?php echo url_for('sf_filebase_display_image', array('file'=>$node->getId(), 'width'=>100, 'height'=>100))?>" alt="<?php echo $file->getFilename()?>"/>
                  <?php endif?>
                  <div>
                    <span class="file-title file-title-file">
                      <?php echo wordwrap(truncate_text($node->getFilename(), 32), 16, '<br/>', true)?>
                    </span>
                  </div>
                </a>
                <span class="file-controls">
                  <?php echo link_to('<span>'.__('Edit').'</span>', 'sf_filebase_file_edit', array('id'=>$node->getId()), array('class'=>'file-edit-link') )?>
                  <?php echo link_to('<span>'.__('Delete').'</span>', '@sf_filebase_file_delete?id='.$node->getId(), array('class'=>'file-delete-link', 'method'=>'delete', 'confirm'=>__('Do you really want to delete this file?')))?>
                </span>
              </div>
            </li>
          <?php endif?>
        <?php endif?>
      <?php endforeach;?>
    <?php endif?>
  </ul>
</div>