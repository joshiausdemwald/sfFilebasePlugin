<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');

try
{
  $t = new lime_test(37, new lime_output_color());
  $t->diag('Ok, let\'s take a look... These tests are as incomplete as tests can be.');
  $t->diag('Any exeptions thrown during testrun may cause in file-permission issues. After a test-run failed, please manually clean up the standard-filebase-directory unter sfConfig::get(sf_upload_dir) and run php ./symfony fix-perms task as a system administrator.');

  ###  INSTANCIATING FILEBASE
  $t->isa_ok($f = new sfFilebasePlugin(), 'sfFilebasePlugin', 'sfFilebasePlugin instanziated.');
  $t->ok($f->getCacheDirectory()->fileExists(), 'sfFilebasePlugin::getCacheDirectory()::fileExists() is true');
  
  ### CHECK ON FILES
  $t->diag('Test some file properties on a non existing file.');
  $t->isa_ok($f3 = $f['notexist'], 'sfFilebasePluginFile', 'Fetched a (non existing) instanceof of sfFilebasePluginFile via sfFilebasePlugin::offsetGet() (array access).');
  $t->ok(!$f3->fileExists(), 'sfFilebasePluginFile::fileExists returns false');
  $t->ok(!$f3->isWritable(), 'Non existing files are not writable');
  $t->ok(!$f3->isReadable(), 'Non existing files are not readable');

  ### CREATE A NEW FILE
  $t->diag('Test some file properties on a existing file...');
  $t->isa_ok($f4 = $f->touch('analyzeme', 0777), 'sfFilebasePluginFile', 'File created by sfFilebasePlugin::touch() with permissions 0777');
  $t->ok($f4->fileExists(), 'The new file exists in sf');
  $t->ok($f4->isReadable(), 'The new file is readable');
  $t->ok($f4->isWritable(), 'The new file is writable');

  $t->diag('Locate some files by its hash');
  $t->ok($hash = $f4->getHash(), sprintf('sfFilebasePluginFile::getHash() returns %s', $hash));
  $t->isa_ok($f->getFileByHash($hash), 'sfFilebasePluginFile', sprintf('sfFilebasePlugin::getFileByHash(%s) returns the file.', $hash));

  $t->diag('Test ArrayAccess');
  $t->isa_ok($f4 = $f['analyzeme'], 'sfFilebasePluginFile', 'Fetched a file by ArrayAccess::offsetGet()');
  $t->ok(!array_key_exists('lalala', $f), 'ArrayAccess::OffsetExists(lalala) returns false');

  ### TEST CREATE DIRECTORY
  $t->diag('Performing some operations on the filebase.');
  $t->isa_ok($d1 = $f->mkDir('test'), 'sfFilebasePluginDirectory', 'sfFilebasePlugin::mkDir(): Created new directory.');
  $t->isa_ok($f1 = $f->touch('test/testfile'), 'sfFilebasePluginFile', 'sfFilebasePlugin::touch() created an empty file.');
  $t->isa_ok($f2 = $f1->copy('test/test2'), 'sfFilebasePluginFile', 'sfFilebasePluginFile::copy() copied the file');

  $t->ok($f2->fileExists(), 'sfFilebasePluginFile::fileExists() is true for the copied file');

  $t->diag('Try to copy the whole directory');
  $t->isa_ok($d2 = $d1->copy('testdir2'), 'sfFilebasePluginDirectory', 'sfFilebasePluginDirectory::copy() copied the directory to a new one');
  $d3 = $f->mkdir('testdir3');
  $t->isa_ok($d1->copy('testdir3'), 'sfFilebasePluginDirectory', 'sfFilebasePluginDirectory::copy() copied the directory into a existing one.');
  $t->isa_ok($d1->copy('testdir3', true), 'sfFilebasePluginDirectory', 'Copying of the directory overriding existing content');

  ### CHMOD
  $t->diag ('Playing with file properties');
  $t->ok($perms = $f1->chmod(0777)->getPerms(), sprintf('Changed mod of file to %s', decbin($perms)));
  $t->ok($perms = $d3->chmod(0777, true)->getPerms(), sprintf('Recursively changed mod of a directory to %s', decbin($perms)));

  $t->diag('Test the renaming of files and directories');
  $t->isa_ok($f2 = $f2->rename('abcde'), 'sfFilebasePluginFile', 'Rename a file ...');
  $t->isa_ok($d3 = $d3->rename('abcdefg'), 'sfFilebasePluginDirectory', 'Rename a directory ...');

  $t->diag('Try to move files and directories');
  $d4 = $f->mkdir ('target', 0777);

  $t->isa_ok($f2 = $f2->move($d4), 'sfFilebasePluginFile', sprintf('Move file to empty directory, new path: %s', $f2));
  $t->isa_ok($d3 = $d3->move($d4), 'sfFilebasePluginDirectory', sprintf('Move directory to empty directory, new path: %s', $d3));
  
  # TRY TO CHANGE OWNER
  try
  {
    $t->ok($user = $f1->chown(get_current_user())->getOwner(), sprintf('Changed owner of file to ID %s', $user));
  }
  catch (Exception $e)
  {
    $t->fail($e);
  }

  # TRY TO CHANGE GROUP
  try
  {
    $t->ok($user = $f1->chgrp(get_current_user())->getOwner(), sprintf('Changed group of file to ID %s', $user));
  }
  catch (Exception $e)
  {
    $t->fail($e);
  }

  # TRY TO CHANGE OWNER
  try
  {
    $t->ok($user = $d3->chown(get_current_user(), true)->getOwner(), sprintf('Recursively Changed owner of directory to ID %s', $user));
  }
  catch (Exception $e)
  {
    $t->fail($e);
  }

  # TRY TO CHANGE GROUP
  try
  {
    $t->ok($user = $d3->chgrp(get_current_user(), true)->getOwner(), sprintf('Recursively Changed group of directory to ID %s', $user));
  }
  catch (Exception $e)
  {
    $t->fail($e);
  }
  
  ### CLEANUP
  $t->diag('Cleanup...');
  $t->ok($f1->delete(), 'Deleted file.');
  $t->ok($f2->delete(), 'Deleted file.');
  $t->ok($f4->delete(), 'Deleted file.');
  $t->ok($d1->delete(), 'Deleted empty directory.');
  $t->ok($d2->delete(true), 'Deleted non empty directory.');
  $t->ok($d3->delete(true), 'Deleted non empty directory.');
  $d4->delete(true);
}
catch(Exception $e)
{
  $t->fail((string)$e);
}