<?php

class PluginSfFilebaseFile extends BasesfFilebaseFile
{
  public function __toString()
  {
    try
    {
      $filebase = sfFilebasePlugin::getInstance();
      $pathname = $this->getPathname();
      $file = $filebase[$pathname];
      return $file->getName();
    }
    catch (Exception $e)
    {
      return (string)$e;
    }
  }
}
