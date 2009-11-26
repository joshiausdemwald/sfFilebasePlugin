<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(35, new lime_output_color());
$t->isa_ok($f = sfFilebasePlugin::getInstance('test'), 'sfFilebasePlugin', 'sfFilebasePlugin instanziated.');

$t->diag('Lets load some files');

$t->diag('Test mime type detection of images, with extension');

$image_png          = $t->isa_ok($f['images/gif.gif'], 'sfFilebasePluginImage');
$image_png24        = $t->isa_ok($f['images/jpg_rgb.jpg'], 'sfFilebasePluginImage');
$image_gif          = $t->isa_ok($f['images/jpg_cmyk.jpg'], 'sfFilebasePluginImage');
$image_jpeg         = $t->isa_ok($f['images/png_24bit.png'], 'sfFilebasePluginImage');
$image_cmyk_jpeg    = $t->isa_ok($f['images/png_8bit.png'], 'sfFilebasePluginImage');
$image_psd          = $t->isa_ok($f['images/testbild.psd'], 'sfFilebasePluginFile');

$t->diag('Unicode tests, without extensions.');

$t->isa_ok($utf16 = $f['textsamples/test_utf16'], 'sfFilebasePluginFile');
$t->isa_ok($utf16be = $f['textsamples/test_utf16be'], 'sfFilebasePluginFile');
$t->isa_ok($utf16le = $f['textsamples/test_utf16le'], 'sfFilebasePluginFile');
$t->isa_ok($utf32 = $f['textsamples/test_utf32'], 'sfFilebasePluginFile');
$t->isa_ok($utf7 = $f['textsamples/test_utf7'], 'sfFilebasePluginFile');
$t->isa_ok($ucs2 = $f['textsamples/test_ucs2'], 'sfFilebasePluginFile');
$t->isa_ok($ucs4 = $f['textsamples/test_ucs4'], 'sfFilebasePluginFile');

$t->is($utf16->getMimeType(), 'text/plain', 'Utf16 mime type ist text/plain');
$t->is($utf16be->getMimeType(), 'text/plain', 'Utf16be mime type ist text/plain');
$t->is($utf16le->getMimeType(), 'text/plain', 'Utf16le mime type ist text/plain');
$t->is($utf32->getMimeType(), 'text/plain', 'Utf32 mime type ist text/plain');
$t->is($utf7->getMimeType(), 'text/plain', 'Utf7 mime type ist text/plain');
$t->is($ucs2->getMimeType(), 'text/plain', 'Ucs2 mime type ist text/plain');
$t->is($ucs4->getMimeType(), 'text/plain', 'Ucs4 mime type ist text/plain');

$t->diag('Unicode tests, with extensions.');

$t->isa_ok($utf16 = $f['textsamples/test_utf16.txt'], 'sfFilebasePluginFile');
$t->isa_ok($utf16be = $f['textsamples/test_utf16be.txt'], 'sfFilebasePluginFile');
$t->isa_ok($utf16le = $f['textsamples/test_utf16le.txt'], 'sfFilebasePluginFile');
$t->isa_ok($utf32 = $f['textsamples/test_utf32.txt'], 'sfFilebasePluginFile');
$t->isa_ok($utf7 = $f['textsamples/test_utf7.txt'], 'sfFilebasePluginFile');
$t->isa_ok($ucs2 = $f['textsamples/test_ucs2.txt'], 'sfFilebasePluginFile');
$t->isa_ok($ucs4 = $f['textsamples/test_ucs4.txt'], 'sfFilebasePluginFile');

$t->is($utf16->getMimeType(), 'text/plain', 'Utf16 mime type ist text/plain');
$t->is($utf16be->getMimeType(), 'text/plain', 'Utf16be mime type ist text/plain');
$t->is($utf16le->getMimeType(), 'text/plain', 'Utf16le mime type ist text/plain');
$t->is($utf32->getMimeType(), 'text/plain', 'Utf32 mime type ist text/plain');
$t->is($utf7->getMimeType(), 'text/plain', 'Utf7 mime type ist text/plain');
$t->is($ucs2->getMimeType(), 'text/plain', 'Ucs2 mime type ist text/plain');
$t->is($ucs4->getMimeType(), 'text/plain', 'Ucs4 mime type ist text/plain');