<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author joshi
 */
class Event
{
  /**
   * Source of the Event (the subject)
   *
   * @var EventSubject $subject
   */
  private $subject;

  /**
   * Name of the Event
   *
   * @var string $name
   */
  private $name;

  /**
   * Struct with optional event arguments.
   * 
   * @var Struct $arguments
   */
  private $arguments;
  
  /**
   * Constructor
   *
   * @param object $source
   * @param string $eventname
   */
  public function __construct(IEventSubject $subject, $event_string, Struct $arguments = null)
  {
    $this->subject = $subject;
    $this->name = $event_string;
    $this->arguments = $arguments;
  }

  /**
   * Returns the name of the Event.
   *
   * @return string $name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Returns the subject.
   *
   * @return EventSubject $subject
   */
  public function getSubject()
  {
    return $this->subject;
  }

  /**
   * Returns optional argument Struct
   *
   * @return Struct $arguments
   */
  public function getArguments()
  {
    return $this->arguments;
  }
}
