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
   * Checks if the file exists in file system. If it does not exists,
   * the validator screams.
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function postValidate(Doctrine_Event $event)
  {
    if($this->_options['checkExistence'])
    {
      $file = $event->getInvoker()->getFile();
      if(!$file->fileExists())
      {
        $error_stack = $event->getInvoker()->getErrorStack();
        $error_stack->add($this->_options['name'], 'The file does not exist.');
      }
    }
  }
}
