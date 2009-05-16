<?php

class PluginSfFilebaseTag extends BasesfFilebaseTag
{
  public function __toString()
  {
    return (string)$this->getTag();
  }
}
