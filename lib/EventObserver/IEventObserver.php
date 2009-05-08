<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventObserver
 *
 * @author joshi
 */
interface IEventObserver
{
  
  /**
   * @param Event $event
   */
  public function notify(Event $event);
}
