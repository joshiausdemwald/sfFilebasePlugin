<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if($_SERVER['argc']<2)
  die("Usage: $ php FilebaseTest.php application [environment]\r\nPlease call the test using a valid symfony app and environment. This is due to the fact that we test a doctrine behaviour which relies on a dataabase connection.\r\nExaple usage: $ php FilebaseTest.php frontend test");

$_test_dir = realpath(dirname(__FILE__).'/..');

require_once(dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php');
$configuration = new ProjectConfiguration(realpath($_test_dir.'/../../../'));
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');

// INITIALIZING DATABASE
$app_conf = ProjectConfiguration::getApplicationConfiguration($_SERVER['argv'][1], $_SERVER['argv'][2], true);
sfContext::createInstance($app_conf)->loadFactories();
