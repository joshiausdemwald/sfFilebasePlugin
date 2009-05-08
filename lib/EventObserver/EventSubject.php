<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventSubject
 *
 * @author joshi
 */
abstract class EventSubject implements IEventSubject
{
  /**
   * List of EventObservers
   *
   * @var array $observers
   */
  protected $observers;

  /**
   * Adds an EventObserver to this Module
   *
   * @param EventObserver $observer
   */
  public function addObserver(IEventObserver $observer, $string_event = self::NOTIFY)
  {
    ! isset($this->observers[$string_event]) && $this->observers[$string_event] = array();
    ! in_array($observer, $this->observers[$string_event]) && $this->observers[$string_event][] = $observer;
  }

  /**
   * Returns array of IEventObservers.
   *
   * @return array IEventObserver $observer
   */
  public function getObservers()
  {
    return $this->observers;
  }

  /**
   * Removes an observer from list.
   *
   * @param EventObserver $observer
   * @param string $string_event
   */
  public function removeObserver(IEventObserver $observer, $string_event = self::NOTIFY)
  {
    unset($this->observers[$string_event][array_search($observer, $this->observers[$string_event])]);
  }

  /**
   * Fires an event.
   *
   * @param string $string_event
   */
  public function fireEvent($string_event = self::NOTIFY)
  {
    // Notify all global observers
    $string_event !== self::NOTIFY && self::fireEvent();

    // Notify all local event observers
    if(isset($this->observers[$string_event]))
    {
      $event = new Event($this, $string_event);
      foreach($this->observers[$string_event] AS $observer)
      {
        $observer->notify($event);
      }
    }
  }
}
?>
