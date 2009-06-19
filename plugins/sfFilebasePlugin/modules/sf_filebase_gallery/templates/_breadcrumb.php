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
<p class="filebase-breadcrumb">
  <?php if(!$node->getNode()->isRoot()):?>
    <?php foreach($node->getNode()->getAncestors() AS $p):?>
      <?php echo link_to($p->getFilename(), 'sf_filebase_gallery', array('id'=>$p->getId()))?> /
    <?php endforeach?>
  <?php endif?>
  <?php echo $node->getFilename()?>
</p>