<?php
/**
 * @author    Joshi
 * @copyright Joshi
 * @package   de.optimusprime.util.filebase
 */
class RecursiveFilebaseIteratorIterator extends RecursiveIteratorIterator
{
  private $tokenBegin;
  private $tokenEnd;
  public function __construct(RecursiveIterator $iterator, $tokenBegin = "", $tokenEnd = "", $mode=RecursiveFilebaseIteratorIterator::SELF_FIRST, $flags=0)
  {
    $this->tokenBegin = $tokenBegin;
    $this->tokenEnd = $tokenEnd;
    parent::__construct($iterator, $mode, $flags); 
  }
  
  public function beginChildren()
  {
    echo $this->tokenBegin;
  }
  
  public function endChildren()
  {
    echo $this->tokenEnd;
  }
}