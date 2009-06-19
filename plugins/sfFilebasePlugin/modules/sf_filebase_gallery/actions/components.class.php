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
class sf_filebase_galleryComponents extends sfComponents
{
  public function executeBreadcrumb(sfWebRequest $request)
  {
    
  }

  public function executeTagcloud(sfWebRequest $request)
  {
    $tags = Doctrine_Query::create()->select('f.tags')->from('sfAbstractFile f')->
    execute(array(), Doctrine::HYDRATE_ARRAY);
    $flat_tags = array();
    foreach ($tags AS $tag)
    {
      $tagss = explode(' ', $tag['tags']);
      foreach ($tagss AS $t)
      {
        if(!empty($t))
        {
          if(array_key_exists($t, $flat_tags))
          {
            $flat_tags[$t] = $flat_tags[$t] + 1;
          }
          else
          {
            $flat_tags[$t] = 1;
          }
        }
      }
    }
    $this->tags = $flat_tags;
  }
}