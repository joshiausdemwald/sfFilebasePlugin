<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author joshi
 */
class Doctrine_Template_Listener_File extends Doctrine_Record_Listener
{
  /**
   * Array of timestampable options
   *
   * @var string
   */
  protected $_options = array();

  /**
   * __construct
   *
   * @param string $options
   * @return void
   */
  public function __construct(array $options)
  {
      $this->_options = $options;
  }

  /**
   * Set the created and updated Timestampable columns when a record is inserted
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preInsert(Doctrine_Event $event)
  {
//      if ( ! $this->_options['created']['disabled']) {
//          $createdName = $event->getInvoker()->getTable()->getFieldName($this->_options['created']['name']);
//          $modified = $event->getInvoker()->getModified();
//          if ( ! isset($modified[$createdName])) {
//              $event->getInvoker()->$createdName = $this->getTimestamp('created');
//          }
//      }
//
//      if ( ! $this->_options['updated']['disabled'] && $this->_options['updated']['onInsert']) {
//          $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
//          $modified = $event->getInvoker()->getModified();
//          if ( ! isset($modified[$updatedName])) {
//              $event->getInvoker()->$updatedName = $this->getTimestamp('updated');
//          }
//      }
  }

  /**
   * Set updated Timestampable column when a record is updated
   *
   * @param Doctrine_Event $evet
   * @return void
   */
  public function preUpdate(Doctrine_Event $event)
  {
//      if ( ! $this->_options['updated']['disabled']) {
//          $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
//          $modified = $event->getInvoker()->getModified();
//          if ( ! isset($modified[$updatedName])) {
//              $event->getInvoker()->$updatedName = $this->getTimestamp('updated');
//          }
//      }
  }

  /**
   * Set the updated field for dql update queries
   *
   * @param Doctrine_Event $evet
   * @return void
   */
  public function preDqlUpdate(Doctrine_Event $event)
  {
//      if ( ! $this->_options['updated']['disabled']) {
//          $params = $event->getParams();
//          $updatedName = $event->getInvoker()->getTable()->getFieldName($this->_options['updated']['name']);
//          $field = $params['alias'] . '.' . $updatedName;
//          $query = $event->getQuery();
//
//          if ( ! $query->contains($field)) {
//              $query->set($field, '?', $this->getTimestamp('updated'));
//          }
//      }
  }

  /**
   * Gets the timestamp in the correct format based on the way the behavior is configured
   *
   * @param string $type
   * @return void
   */
//  public function getFile()
//  {
//    $options = $this->_options[$type];
//
//    if ($options['expression'] !== false && is_string($options['expression'])) {
//        return new Doctrine_Expression($options['expression']);
//    } else {
//        if ($options['type'] == 'date') {
//            return date($options['format'], time());
//        } else if ($options['type'] == 'timestamp') {
//            return date($options['format'], time());
//        } else {
//            return time();
//        }
//    }
//  }
}
