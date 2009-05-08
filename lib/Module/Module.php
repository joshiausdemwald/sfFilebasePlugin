<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Module
 *
 * @author joshi
 */
class Module extends EventSubject implements IEventObserver
{
  /**
   *
   *  @var string
   */
  private $name;

  /**
   *
   * @var string
   */
  private $author;
  
  /**
   *
   * @var Struct $dependancies
   */
  private $dependancies;

  /**
   *
   * @var string
   */
  private $mainClassName;

  /**
   *
   * @var string
   */
  private $fileName;
  
  /**
   * List of Event-Listeners
   * 
   * @var Struct $eventListeners
   */
  private $eventListeners;

  private $instance;
  
  /**
   * Holds reference to sfContext instance
   * 
   * @var sfContext $context
   */
  private $context;

  /**
   * Constructor
   *
   * @param Struct $attributes
   */
  function __construct(sfContext $context, Struct $attributes)
  {
    $this->name           = $attributes->getModule()->getName();
    $this->author         = $attributes->getModule()->getAuthor();
    $this->dependancies   = $attributes->getModule()->getDependancies();
    $this->eventListeners = $attributes->getModule()->getEventListeners();
  }

  /**
   * Returns instance of application Context.
   *
   * @return sfContext
   */
  public function getContext()
  {
    return $this->context;
  }

  /**
   * Returns current request stream representation
   *
   * @return sfWebRequest $request
   */
  public function getRequest()
  {
    return $this->context->getRequest();
  }

  /**
   * Returns current response stream representation from
   * context.
   *
   * @return sfWebResponse $response
   */
  public function getResponse()
  {
    return $this->context->getResponse();
  }

  /**
   * Returns all dependancies as a textual
   * list (Struct)
   *
   * @return Struct $dependancies
   */
  public function getDependancies()
  {
    return $this->dependancies;
  }

  /**
   * Returns all desired EventListeners
   * as a textual list (Struct)
   * 
   * @return Struct $eventListeners
   */
  public function getEventListeners()
  {
    return $this->eventListeners;
  }

  /**
   * Returns the modules name
   *
   * @return string $name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Event Notifier. Called by subject in fireEvent().
   *
   * Implementation of notify() executes a particular
   * callback-function on this object when defined.
   *
   * @see EventSubject::fireEvent
   * @param Event $event
   */
  public function notify(Event $event)
  {
    $name     = $event->getName();
    $subject  = $event->getSubject();

    // Execute Callback
    $this->{"action_".$name}($event);
  }

  public function action_saved(Event $event)
  {
    echo "lala";
  }
}