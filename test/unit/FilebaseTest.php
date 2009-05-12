<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

$t = new lime_test(75, new lime_output_color());
$t->diag('Ok, let\'s take a look... These tests are as incomplete as tests can be.');
$t->diag('Any exeptions thrown during testrun may cause in file-permission issues. After a test-run failed, please manually clean up the standard-filebase-directory unter sfConfig::get(sf_upload_dir) and run php ./symfony fix-perms task as a system administrator.');

###  INSTANCIATING FILEBASE
$t->isa_ok(new sfFilebasePlugin(), 'sfFilebasePlugin', 'sfFilebasePlugin instanziated.');
$t->diag('Performing some file operations.');

$f = new sfFilebasePlugin();
$t->diag('Lets try to generate a directory with mkDir().');

$dir = null;

try
{
  $t->isa_ok($dir = $f->mkDir('my_dir', 0777), 'sfFilebasePluginDirectory', 'mkDir() successfully created a new directory.');
}
catch (Exception $e)
{
  $t->fail((string)$e->getMessage());
  $t->diag('Maybe the directory already exists....');
}
$t->ok($dir instanceof sfFilebasePluginDirectory, 'mkDir() returns instance of sfFilebasePluginDirectory.');

$t->diag('Directory was created, now try to delete it.');

$t->is($dir->fileExists(), true, 'File check with fileExists() is true.');
$t->is($dir->isWritable(), true, 'File check with is Writable() is true');

try
{
  $success = $dir->delete();
}
catch (Exception $e)
{
  $t->diag((string)$e->getMessage());
  $t->diag('Maybe the directory does not exist or is write protected.');
}
$t->is($success, true, "Return-Value of delete() is true, that means the directory was successfully deleted.");

$t->diag('Lets check this twice:');
$t->is($dir->fileExists(), false, 'fileExists() returns false, that means the directory was really deleted.');

$t->diag('Now I want to rename a directory.');
$t->isa_ok($f->mkDir('my_dir'), 'sfFilebasePluginDirectory', 'Directory "my_dir" successfully created by sfFilebasePlugin::mkDir().');

$t->isa_ok($dir = $dir->rename('my_other_dir'), 'sfFilebasePluginDirectory', 'Directory "my_dir" was successfully renamed to "my_other_dir"');

$t->isa_ok($dir = $dir->rename('my_dir'), 'sfFilebasePluginDirectory', 'Directory re-renamed to "my_dir"');

###  CHMOD
$t->diag('Let\'s chmod the directory');
$t->isa_ok($dir->chmod(0755), 'sfFilebasePluginDirectory', 'chmod() 0755 successful.');
$t->isa_ok($dir->chmod(0777), 'sfFilebasePluginDirectory', 'chmod() 0777 successful.');

### File paths
$t->diag('I want to get the paths');
$t->isa_ok($path = $dir->getRelativePathFromFilebaseDirectory(), 'string', 'getRelativePathFromFilebaseDirectory() returns string');
$t->is(file_exists($f . '/' . $path), true, 'The path was successfully rendered: file_exists() on ' . $f . '/' . $path . ' returns true');
$t->isa_ok($path = $dir->getAbsolutePathFromWebroot(), 'string', 'getAbsolutePathFromWebroot() returns string');

### Copy Files
$t->diag('I want to perform some file operations, but before that i have to copy a file into filebase...');

$t->diag('Fetching test-image');
$t->isa_ok($image = $f->getFilebaseFile(dirname(__FILE__) . '/' . 'test.JPG'), 'sfFilebasePluginImage', 'Image fetched ./test.JPG as instance of sfFilebasePluginImage');

$t->diag('Copying test-image to filebase');
$t->isa_ok($copy=$image->copy($dir . '/test.jpg'), 'sfFilebasePluginImage', 'copy() returns instanceof sfFilebasePluginImage');
$t->diag('Does the copied image exist at the destinated location?');
$t->is($copy->fileExists(), true, 'fileExists() returns true');
$t->diag('Let\'s create a thumbnail...');
$t->isa_ok($tn = $copy->getThumbnail(array('width'=>20)), 'sfFilebasePluginThumbnail', 'Thumbnail created as instanceof sfFilebasePluginThumbnail');

$t->diag('This is nonsense: Open thumbnail and print its content to stdout:');
$t->diag($tn->openFile()->fileGetContents() . "..");
$t->is($tn->fileExists(), true, 'sfFilebasePluginThumbnail::fileExists() returns true.');

$t->isa_ok($copy = $copy->rename('renamed_image.jpg'), 'sfFilebasePluginImage', 'Performing a rename(), returns instanceof sfFilebasePluginImage.');
$t->is($copy->fileExists(), true, 'fileExists() returns true, so renamed image exists in file system.');

$t->diag('Lets resize the original image');
$t->isa_ok($copy->resize(array('1000')), 'sfFilebasePluginImage', 'Resize ok, resize() returns instance of sfFilebasePluginImage');
$t->is($copy->getWidth(), 1000, 'Image now has 1000 pixels width.');
$t->is($copy->getMimeType(), 'image/jpeg', 'getMimeType() says: Image has mime-type image/jpeg');
$t->is($copy->getHumanReadableFileType(), 'jpeg image', 'getHumanReadableFileType() says "jpeg image"');
### Writing stuff into a new file
$t->diag('I want to write some line into my new file...');
$t->isa_ok($new_file = $f->touch('new_file.txt', 0777), 'sfFilebasePluginFile', 'touch() returns a new instanceof sfFilebasePluginFile');
$t->isa_ok($fp = $new_file->openFile('a+'), 'sfFilebasePluginFileObject', 'touch()ed file was opened. Return value is a pointer of type sfFilebasePluginFileObject.');

$t->isa_ok($fp->fwrite("This line is written by tester.\n"), 'integer', 'Successfully written by fwrite()');
$t->isa_ok($fp->fwrite("This second line is written by tester.\n"), 'integer', 'Successfully written by fwrite()');
$t->isa_ok($fp->writeLn("This third line is written by tester."), 'integer', 'Successfully written by writeln()');
$t->isa_ok($fp->write("This fourth line is written by tester."), 'integer', 'Successfully written by write()');
$t->isa_ok($fp->fileGetContents(), 'string', 'Read file successfully by fileGetContents(), value is ' . $fp->fileGetContents());
$t->isa_ok($fp->getRelativePathFromFilebaseDirectory(), 'string', 'Delegation test for getRelativePathFromFilebaseDirectory()');

$t->diag('Testing iteration over file contents');
$t->diag('Begin loop');

foreach($fp as $i => $line)
{
  $t->pass($i . '> ' . $line);
}
$t->diag('End loop');

$t->diag('Creating some files');
$new_fs = array();
for($i=0; $i<10; $i++)
{
  $t->isa_ok($new_f = $f->touch(uniqid()), 'sfFilebasePluginFile', 'touch() created File ' . $new_f);
  $new_fs[] = $new_f;
}

$t->isa_ok($iter = $f->getIterator(), 'sfFilebasePluginRecursiveDirectoryIterator', 'getIterator() returns valid sfFilebasePluginRecursiveDirectoryIterator');

$t->diag('Testing iteration...');
foreach($f AS $file)
{
  $t->ok($file instanceof sfFilebasePluginFile, 'File' . $file . ' is instanceof sfFilebasePluginFile, espacially ' . get_class($file));
}

$t->diag('Picking some file by ArrayAccess-Methods:');
$t->isa_ok($f[$new_fs[3]], 'sfFilebasePluginFile', 'File with index 3 is a sfFilebasePluginFile named '.$f[$new_fs[3]]);

$t->diag('Cleanup...');

foreach($new_fs AS $file)
{
  $t->is($file->delete(), true, 'Test file ' . $file .' successfully delete()ed.' );
}

$t->is ($new_file->delete(), true, 'The touch()ed file deleted by delete()');
$t->is ($f->clearCache(), true, 'Cache was succesfully cleared, cacheClear() returns true.');
$t->is($dir->deleteRecursive(), true, 'Folder successfully deleted throughout deleteRecursive()');
