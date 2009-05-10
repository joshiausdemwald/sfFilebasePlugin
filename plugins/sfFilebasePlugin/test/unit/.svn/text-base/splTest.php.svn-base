<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(3, new lime_output_color());
touch(dirname(__FILE__).'/sample.txt');
chmod(dirname(__FILE__).'/sample.txt', 0777);

$t->isa_ok($file = new SplFileInfo(dirname(__FILE__).'/sample.txt'), 'SplFileInfo');
$t->isa_ok($fp = $file->openFile('a'), 'SplFileObject');
$t->is($fp->fwrite('test'), true);
