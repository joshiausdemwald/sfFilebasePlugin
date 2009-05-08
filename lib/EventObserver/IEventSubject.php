<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subject
 *
 * @author joshi
 */
interface IEventSubject {

  const NOTIFY = 'event:plain_notification';

  /**
	 * @param EventObserver $eventObserver
   * @param string $event
	 */
	public function addObserver (IEventObserver $SplObserver, $str_event = self::NOTIFY);

	/**
	 * @param EventObserver $eventObserver
   * @param string $event
	 */
	public function removeObserver (IEventObserver $SplObserver, $str_event = self::NOTIFY);

  /**
   *
   * @param string $str_event
   */
	public function fireEvent ($str_event = self::NOTIFY);
}
