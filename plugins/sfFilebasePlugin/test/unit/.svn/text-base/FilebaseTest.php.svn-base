<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(dirname(__FILE__).'/../bootstrap/unit.php');
try
{
  $t = new lime_test(87, new lime_output_color());
  $t->diag('Ok, let\'s take a look... These tests are as incomplete as tests can be.');
  $t->diag('Any exeptions thrown during testrun may cause in file-permission issues. After a test-run failed, please manually clean up the standard-filebase-directory unter sfConfig::get(sf_upload_dir) and run php ./symfony fix-perms task as a system administrator.');

  ###  INSTANCIATING FILEBASE
  $t->isa_ok($f = new sfFilebasePlugin(realpath(dirname(__FILE__) . '/../assets')), 'sfFilebasePlugin', 'sfFilebasePlugin instanziated.');
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

  $t->diag('Test if a file lies within the filebase');
  $t->ok(!$f->getFilebaseFile('/test')->getIsInFilebase(), '/test does not lie within the filebase directory');
  $t->ok($f4->getIsInFilebase(), 'This file lies within its filebase directory');
  
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

  $t->diag('Open a file to edit');
  $t->isa_ok($p = $f1->openFile('w+'), 'sfFilebasePluginFileObject', 'Open a pointer...');
  $t->ok($p->writeLn('This is line 1'), 'sfFilebasePluginFileObject::writeLn(): Write a line...');
  $t->ok($p->writeLn('line two...'), 'sfFilebasePluginFileObject::writeLn(): Write a line...');
  $t->ok($p->writeLn('And the 3rd line'), 'sfFilebasePluginFileObject::writeLn(): Write a line...');
  $t->ok($p->writeLn('And the 4th'), 'sfFilebasePluginFileObject::writeLn(): Write a line...');
  $t->ok($p->write('And the last line...'), 'sfFilebasePluginFileObject::write(): Write a line...');

  $t->isa_ok($f1->cache(), 'sfFilebasePluginFile', 'Cache a single file');
  $t->isa_ok($d3->cache(), 'sfFilebasePluginDirectory', 'Cache a whole directory');

  $p->rewind();
  while(!$p->eof())
  {
    echo $t->ok($line = $p->fgets(), 'Read a line: "' . trim($line) . '"');
  }

  # SOME IMAGE STUFF
  $t->diag('Test a few image transform capabilities');
  $t->isa_ok($i1 = $f['150px-PHP_Logo.svg.png'], 'sfFilebasePluginImage', '$f[150px-PHP_Logo.svg.png]: Retrieve image file.');
  $t->ok($i1->isImage(), 'sfFilebasePlugin::getIsImage() : The retrieved image file is an image');
  $t->ok($i1->isWebImage(), 'sfFilebasePlugin::getIsWebImage() : The retrieved image file is a web image');
  $t->ok($width = $i1->getWidth(), 'sfFilebasePluginImage::getWidth() returned ' . $width);
  $t->ok($height= $i1->getHeight(), 'sfFilebasePluginImage::getHeight() returned ' . $height);
  $t->ok($mime = $i1->getMimeType(), 'sfFilebasePluginImage::getMimeType() returned ' . $mime);
  $t->diag('Check the sfImageTransformPlugin mixins');
  $t->isa_ok($i2 = $i1->copy('test.png'), 'sfFilebasePluginImage', 'sfFilebasePluginImage::copy() Copying of the image was successful');
  $t->isa_ok($i2->thumbnail(10, 10), 'sfFilebasePluginImage', 'Image transform (thumbnail) successful, assuming all transforms work.');
  $t->isa_ok($i2->save(), 'sfFilebasePluginImage', 'Thumbnail successfully saved');
  $t->ok(strlen(($str = $i2->getBinaryString())) > 0, "The resulting image stream: \r\n" . $str);

  ## TEST THE UTILS
  $t->diag('sfFilebasePluginUtil::isAbsolutePathname()');
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('/hanswurst'));
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('/hanswurst/kaese'));
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('c:/hanswurst/kaese'));
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('c:/hanswurst'));
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('c:\\hanswurst\\kaese'));
  $t->ok(sfFilebasePluginUtil::isAbsolutePathname('c:\\hanswurst\\kaese'));

  $t->diag('sfFilebasePluginUtil::realpath()');
  $t->ok($p = sfFilebasePluginUtil::realpath('/hanswurst'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('/hanswurst/kaese'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('c:/hanswurst/kaese'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('c:/hanswurst'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('c:\\hanswurst\\kaese'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('c:\\hanswurst\\kaese'), $p);
  $t->ok(!$p = sfFilebasePluginUtil::realpath('c:\\..\\hanssimaus\\hanswurst\\kaese'), 'false');
  $t->ok($p = sfFilebasePluginUtil::realpath('c:\\hanssimaus\\hanswurst\\..\\kaese'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('c:\\hanssimaus\\hanswurst\\würstelchen\\..\\..\\..\\kaese\\..'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('/hanswurst/kaese/../'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('hanswurst/kaese/../naseweis'), $p);
  $t->ok($p = sfFilebasePluginUtil::realpath('hanswurst/../kaese/../naseweis'), $p);

  # DO THE DOCTRINE BEHAVIOUR TEST. USE THIS ONLY IF THE MODEL IS GENERATED
  if(class_exists('testerer'))
  {
    $t->diag('Try something with the Doctrine-Behaviour');
    $f_test = new testerer();
    $t->isa_ok($f_test->setFile($i2), 'Doctrine_Template_File', 'Bound file to doctrine record object');

    try {
      $f_test->save();
      $t->pass('Saved doctrine record');
    }
    catch(Exception $e){
      $t->fail('Failed saving doctrine record');
    }
    $t->isa_ok($f_test->getFile(), 'sfFilebasePluginImage', 'Doctrine_Template_File::getFile() returns sfFilebasePluginFile');

    $f_test->setFile($d3);
    
    $f_test->save();
    $t->isa_ok($f_test->getFile(), 'sfFilebasePluginDirectory', 'Set and get a sfFilebasePluginDirectory.');

    $t->diag('Try storing a non existent file in database');
    $f_test->setFile('hasdgsasgd/sadgdg');
    $t->ok(!$f_test->isValid(), 'Validation of model failed' );
  }

  $t->diag('Do something with the file permissions. Beware of os-dependent test cases.');
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
  $t->ok($i2->delete(), 'Deleted the previously copied image');
  $t->ok($f->clearCache(), 'Cleared the cache');
  $d4->delete(true);
  Doctrine::getTable('testerer')->findAll()->delete();
}
catch(Exception $e)
{
  $t->fail((string)$e);
}