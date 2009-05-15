<?php

class PluginSfFilebaseDirectory extends BasesfFilebaseDirectory
{
  public function __toString()
  {
    try
    {
      $filebase = sfFilebasePlugin::getInstance();
      $pathname = $this->getsfFilebaseFile()->getPathname();
      $file = $filebase[$pathname];
      return $file->getName();
    }
    catch (Exception $e)
    {
      return (string)$e;
    }
  }
}
