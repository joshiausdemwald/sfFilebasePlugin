<?php use_helper('I18N')?>
<p class="filebase-breadcrumb">
  <?php if(!$node->getNode()->isRoot()):?>
    <?php foreach($node->getNode()->getAncestors() AS $p):?>
      <?php $title = $p->getNode()->isRoot() ? __('{root}') : $p->getFilename()?>
      <?php echo link_to($title, 'sf_filebase_overview', array('id'=>$p->getId()))?> /
    <?php endforeach?>
  <?php endif?>
  <?php echo $node->getNode()->isRoot() ? __('{root}') : $node->getFilename()?>
</p>