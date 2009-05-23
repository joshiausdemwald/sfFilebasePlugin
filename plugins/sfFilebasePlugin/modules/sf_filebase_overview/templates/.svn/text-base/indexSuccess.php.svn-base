<?php use_stylesheet('/sfFilebasePlugin/css/main.css');?>
<ul id="Sf_Filebase_Overview">
  <li>
    <a href="#root" rel="root" class="toggle-row file-title file-title-directory"><?php echo $root->getFilename()?></a>
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
        <a rel="<?php echo $id?>" href="#<?php echo $id?>" class="toggle-row file-title file-title-directory">» <?php echo $node?></a>
        <ul id="<?php echo $id?>" class="clearfix">
          <?php recurse($node)?>
        </ul>
      </li>
    <?php else:?>
      <li class="file">
        <div class="contents">
          <?php $file = sfFilebasePlugin::getInstance()->getFilebaseFile($node->getHash())?>
          <?php if($file instanceof sfFilebasePluginImage):?>
            <img src="<?php echo url_for('sf_filebase_display_image', array('file'=>$node->getId(), 'width'=>150, 'height'=>150))?>" alt="<?php echo $file->getFilename()?>"/>
          <?php else:?>
            <span class="file-title file-title-file">
              <?php echo $node?>
            </span>
          <?php endif?>
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