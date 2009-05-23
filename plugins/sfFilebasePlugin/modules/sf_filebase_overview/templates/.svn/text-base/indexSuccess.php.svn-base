<?php use_helper('I18N')?>
<?php use_helper('Text')?>
<h2>sfFilebase gallery</h2>
<ul id="Sf_Filebase_Overview">
  <li class="directory">
    <div class="contents">
      <a href="#root" rel="root" class="toggle-row file-title file-title-directory">»&nbsp;<?php echo $root->getFilename()?></a>
    </div>
    <ul id="root" class="clearfix">
      <?php recurse($root)?>
    </ul>
  </li>
</ul>

<?php function recurse($parent){?>
  <?php foreach($parent->getNode()->getChildren() AS $node):?>
    <?php if($node instanceof sfFilebaseDirectory && $node->getNode()->hasChildren()):?>
      <?php
        $id=md5(uniqid(rand(), true));
      ?>
      <li class="directory" style="clear:both">
        <div class="contents">
          <a rel="<?php echo $id?>" href="#<?php echo $id?>" class="toggle-row file-title file-title-directory">»&nbsp;<?php echo $node?></a>
        </div>
        <ul id="<?php echo $id?>" class="clearfix">
          <?php recurse($node)?>
        </ul>
      </li>
    <?php else:?>
      <?php $file = sfFilebasePlugin::getInstance()->getFilebaseFile($node->getHash())?>
      <li class="file<?php $file instanceof sfFilebaseImage && print ' file-image'?>">
        <div class="contents">
          <?php if($file instanceof sfFilebasePluginImage):?>
            <img src="<?php echo url_for('sf_filebase_display_image', array('file'=>$node->getId(), 'width'=>40, 'height'=>40))?>" alt="<?php echo $file->getFilename()?>"/>
          <?php endif?>
          <div>
            <p class="file-title file-title-file">
              <strong><?php echo __('Title');?></strong>:
              <?php echo truncate_text($node->getFilename(), 20)?>
            </p>
            <p class="file-comment"><strong><?php echo __('Comment');?></strong>: <?php echo truncate_text($node->getComment(), 100)?></p>
            <p class="file-tags"><strong><?php echo __('Tags')?></strong>: <?php echo $node->getTagsAsString(' ')?></p>
            <p class="file-controls">
              <?php echo link_to('» edit','sf_filebase_file_edit', array('id'=>$node->getId()))?>
              <?php echo link_to('&times; delete','sf_filebase_file_delete', array('id'=>$node->getId()), array('onclick'=>'return confirm("'.__('Do you really want to do this?').'")'))?>
            </p>
          </div>
        </div>
      </li>
    <?php endif?>
    
  <?php endforeach;?>
<?php }?>
<script type="text/javascript">
  //<![CDATA[
    Ext.onReady(function(ev){
      var rows = Ext.query('.toggle-row');
      Ext.each(rows, function(row)
      {
        row = Ext.get(row);
        var rel=row.dom.getAttribute('rel');
        var target = Ext.get(rel);
        target.hide();
        row.on('click', function(ev)
        {
          ev.preventDefault();
          target.toggle({
            callback: function(el) { el.dom.style.display = el.dom.style.display == 'block' ? 'none' : 'block'; }
          });
        });
      });
    });
  //]]>
</script>