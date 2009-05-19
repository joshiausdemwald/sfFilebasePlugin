<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */
abstract class PluginsfAbstractFile extends BasesfAbstractFile
{
  public function preSave($event)
  {
    $values = $this->getModified(true);

    if(array_key_exists('path', $values) && array_key_exists('sf_filebase_directories_id', $values))
    {
      if($this->getPath() != $this->getParentDirectory()->getPathname())
        throw new LogicException('Path %s differs from directory pathname %s.', $this->getPath(), $this->getParentDirectory()->getPathname());
    }
    elseif(array_key_exists('path', $values))
    {
      $dir_object = $this->getParentDirectory();
      if($dir_object->getPathname() != $this->getPath())
      {
        $dir_object = Doctrine_Query::create()->
            select('*')->
            from('sfFilebaseDirectory d')->
            where('CONCAT(d.path, \'/\', d.filename)=?')->
            execute(array($this->getPath()))->get(0);
      }
      $this->setParentDirectory($dir_object);
    }
    
    // Ensure the constraint between directory flag and
    // path.
    elseif(array_key_exists('sf_filebase_directories_id', $values))
    {
      $f = sfFilebasePlugin::getInstance();
      $path = $this->getParentDirectory()->getPathname() === null ?
        $f->getPathname() : 
        $this->getParentDirectory()->getPathname();
      $this->setPath($path);
    }
  }

  /**
   * Returns the tags as a string representation.
   * @param string $separator
   * @return string $tag_string
   */
  public function getTagsAsString($separator = ', ')
  {
    $tags = array();
    foreach($this->getTags() AS $tag)
    {
      $tags[] = $tag->getTag();
    }
    return implode($separator, $tags);
  }

  /**
   * Sets the tags for this file.
   *
   * @param array $tags
   */
  public function setTags(array $tags)
  {
    Doctrine_Query::create()->
      from('sfFilebaseTag t')->
      delete()->
      where('t.sf_abstract_files_id='.$this->getId())->
      execute();

    foreach ($tags AS $tag)
    {
      $new_tag = new sfFilebaseTag();
      $new_tag->setTag($tag);
      $new_tag->setFile($this);
      $this->tags[] = $new_tag;
    }
  }

  public function __toString()
  {
    return (string)$this->getFilename();
  }

  /**
   *
   * @return string link to the parent directories edit page
   */
  public function getParentDirectoryLink()
  {
    $p = $this->getsfFilebaseDirectoriesId();
    return empty($p) ? '' :
            link_to($this->getParentDirectory()->getPathname(), 'sf_filebase_directory_edit', $this->getParentDirectory());
  }

  public function getPathname()
  {
   if(!$this->getPath() || !$this->getFilename())
   {
     return null;
   }
   return   $this->getPath() . '/' . $this->getFilename() ;
  }

  /**
   * This method is only for the displaying in sfDoctrine Form.
   * It renders an indent for each directory/file.
   *
   * @return <type>
   */
  public function getFilenameIndent()
  {
    $f = sfFilebasePlugin::getInstance();
    return str_repeat('&nbsp;', $f[$this->getPathname()]->getNestingLevel()) . $this->getFilename();
  }
}