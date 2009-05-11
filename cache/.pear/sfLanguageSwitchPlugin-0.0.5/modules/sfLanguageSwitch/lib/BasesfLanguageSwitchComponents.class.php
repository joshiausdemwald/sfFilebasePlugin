<?php

/*
 * This file is part of the symfony package.
 * (c) 2008 Thomas Boerger <tb@mosez.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Language switch component base.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Thomas Boerger <tb@mosez.net>
 * @version    SVN: $Id: BasesfLanguageSwitchComponents.class.php 13033 2008-11-16 18:26:00Z mosez $
 */
class BasesfLanguageSwitchComponents extends sfComponents
{
  /**
   * Get the language switch.
   *
   * @access public
   * @param  void
   * @return void
   */
  public function executeGet()
  {
    $this->initializeSwitch();
    $this->parseUrl();
    $this->parseLanguages();

    $tempvalue = array();
    foreach($this->languages_available as $language => $information)
    {
      $tempvalue[$language] = array();
      $this->pathinfo['sf_culture'] = $language;

      $firstend = false;
      $query = '';
      foreach($this->pathinfo as $key => $value)
      {
	if(!$firstend)
	{
          $query .= '?';
	  $firstend = true;
	}
	else
	{
          $query .= '&';
	}

	$query .= $key . '=' . $value;
      }

      $tempvalue[$language]['query'] = $query;
      
      if(isset($information['title']))
      {
        $tempvalue[$language]['title'] = $information['title'];
      }
      else
      {
        $tempvalue[$language]['title'] = $language;
      }

      if(isset($information['image']))
      {
        $tempvalue[$language]['image'] = $information['image'];
      }
      else
      {
        $tempvalue[$language]['image'] = sfConfig::get('app_sfLanguageSwitch_flagPath', '/sfLanguageSwitch/images/flag') . '/' . $language . '.png';
      }
    }

    $this->languages = $tempvalue;
  }

  /**
   * Initialize the vars for language switch
   *
   * @access protected
   * @param  void
   * @return void
   */
  protected function initializeSwitch()
  { 
    if(method_exists($this->getContext(), 'getRouting'))
    {
      $this->routing = $this->getContext()->getRouting();
    }
    else
    {
      $this->routing = sfRouting::getInstance();
    }
    
    $this->request = $this->getContext()->getRequest();

    $this->languages_available = array(
      'en' => array(
        'title' => 'English',
        'image' => '/sfLanguageSwitch/images/flag/gb.png'
      )
    );
  } 

  /**
   * Parse the requested URL.
   *
   * @access protected
   * @param  void
   * @return void
   */
  protected function parseUrl()
  {
    $pathinfo = $this->routing->parse($this->request->getPathInfo());
    
    if(isset($pathinfo['_sf_route']))
    {
      unset($pathinfo['_sf_route']);
    }

    $this->current_module = $pathinfo['module'];
    unset($pathinfo['module']);

    $this->current_action = $pathinfo['action'];
    unset($pathinfo['action']);

    $this->pathinfo = $pathinfo;
  }

  /**
   * Get available languages by app.yml config.
   *
   * @access protected
   * @param  void
   * @return void
   */
  protected function parseLanguages()
  {
    $this->languages_available = sfConfig::get(
      'app_sfLanguageSwitch_availableLanguages',
      $this->languages_available
    );
  }
}
