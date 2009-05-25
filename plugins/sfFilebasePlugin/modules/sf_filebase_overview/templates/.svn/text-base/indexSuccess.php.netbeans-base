<?php use_helper('I18N')?>
<?php use_helper('Text')?>

<?php include_component('sf_filebase_overview', 'breadcrumb', array('node'=>$parent));?>
<h2>sfFilebase gallery</h2>
<ul id="Sf_Filebase_Overview">
  <?php if($parent->getNode()->hasParent()):?>
    <li class="file file-parent">
      <div class="contents">
        <p>
          <a href="<?php echo url_for('sf_filebase_overview', array('id'=>$parent->getNode()->getParent()->getId()))?>" class="toggle-row file-title file-title-directory"><span>../</span></a>
        </p>
      </div>
    </li>
  <?php endif?>
  <?php if($parent->getNode()->hasChildren()):?>
    <?php foreach($parent->getNode()->getChildren() AS $node):?>
      <?php if($node instanceof sfFilebaseDirectory):?>
        <?php
          $id=md5(uniqid(rand(), true));
        ?>
        <li class="file file-directory">
          <div class="contents">
            <p>
              <a href="<?php echo url_for('sf_filebase_overview', array('id'=>$node->getId()))?>" class="toggle-row file-title file-title-directory"><span><?php echo $node->getFilename()?></span></a>
            </p>
          </div>
        </li>
      <?php else:?>
        <?php $file = sfFilebasePlugin::getInstance()->getFilebaseFile($node->getHash())?>
        <li class="file<?php $file instanceof sfFilebaseImage && print ' file-image'?>">
          <div class="contents">
            <a href="<?php echo url_for('sf_filebase_file_edit', array('id'=>$node->getId()))?>">
              <?php if($file instanceof sfFilebasePluginImage):?>
                <img src="<?php echo url_for('sf_filebase_display_image', array('file'=>$node->getId(), 'width'=>64, 'height'=>64))?>" alt="<?php echo $file->getFilename()?>"/>
              <?php endif?>
              <div>
                <span class="file-title file-title-file">
                  <?php echo $node->getFilename()?>
                </span>
              </div>
            </a>
          </div>
        </li>
      <?php endif?>
    <?php endforeach;?>
  
  <script type="text/javascript">
    //<![CDATA[
      /*Ext.onReady(function(ev){
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
      });*/
    //]]>
  </script>
<?php endif?>
</ul>