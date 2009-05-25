<?php
/**
 * Tagcloud helper (c) by Stefan Koopmanschap
 * http://snippets.symfony-project.org/snippet/240
 *
 * @author Stefan Koopmanschap
 * @copyright Stefan Koopmanschap
 * @package de.optimusprime.sfFilebaseplugin.adminArea
 */
class TagcloudHelper
{
  protected static $smallest;
  protected static $largest;
  protected static $interval;
  protected static $tags  = array();
  protected static $cloud_tags;
  protected static $url = '';
  
  /**
   * Generate the tag array and echo the cloud
   *
   * @return array
   *
   */
  public static function showCloud(array $tags, $url = '')
  {
    self::$url = $url;
    asort($tags);
    reset($tags);
    self::$smallest = current($tags);
    end($tags);
    self::$largest = current($tags);
    $diff = self::$largest - self::$smallest;
    self::$interval = round($diff/3);

    self::$tags = $tags;
    reset(self::$tags);
    self::$cloud_tags = array();
    foreach(self::$tags as $tag => $amount)
    {
      echo link_to($tag, str_replace("%25tag%25", urlencode($tag), self::$url), array('class' => self::getSize($amount))) . ' ';
    }
  }

  /**
   * Get the size (css class) based on the amount
   *
   * @param integer $amount
   * @return array
   */
  private static function getSize($amount) {
    if ($amount == self::$smallest) {
      return 'cloud_xsmall';
    }
    if ($amount == self::$largest) {
      return 'cloud_xlarge';
    }
    if ($amount > self::$smallest + (2*self::$interval)) {
      return 'cloud_large';
    }
    if ($amount > self::$smallest + self::$interval) {
      return 'cloud_medium';
    }
    return 'cloud_small';
  }

}
