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

$rows = array();
foreach ($root->getNode()->getChildren() AS $entry)
{
  if($entry instanceof sfFilebaseDirectory)
  {
    $rows[] = array (
      'id'            => $entry->getId(),
      'text'          => $entry->getFilename(),
      'allowChildren' => true,
      //'checked'       => false,
      'href'          => url_for('sf_filebase_gallery', array('id'=>$entry->getId()))
    );
  }
  else
  {
    $rows[] = array (
      'id'            => $entry->getId(),
      'text'          => $entry->getFilename(),
      'leaf'          => true,
      'allowChildren' => false,
      //'checked'       => false,
      'isTarget'      => false,
      'allowDrop'     => false,
      'href'          => url_for('sf_filebase_file_edit', array('id'=>$entry->getId()))
    );
  }
}
echo json_encode($rows);