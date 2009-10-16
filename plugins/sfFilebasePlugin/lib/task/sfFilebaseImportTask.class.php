<?php
/**
 * This file is part of the sfFilebasePlugin package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   de.optimusprime.sfFilebasePlugin.adminArea
 * @author    Johannes Heinen <johannes.heinen@gmail.com>
 * @license   MIT license
 * @copyright 2007-2009 Johannes Heinen <johannes.heinen@gmail.com>
 */
class sfFilebaseImportTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name.'),
      new sfCommandArgument('source_path', sfCommandArgument::REQUIRED, 'The absolute source directory path, e.g. /path/to/my/images'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment, default dev', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name, default doctrine.', 'doctrine'),
      new sfCommandOption('file_mode', null, sfCommandOption::PARAMETER_REQUIRED, 'The file mode, default to 0777', 0777),
      // add your own options here
    ));

    $this->namespace        = 'sfFilebase';
    $this->name             = 'import';
    $this->briefDescription = 'Imports files and directories into your filebase application database.';
    $this->detailedDescription = <<<EOF
The [sfFilebase:import|INFO] imports files and directories into your filebase application database.
Call it with:
  [php symfony sfFilebase:import|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $root_directory_path = sfConfig::get('app_sf_filebase_plugin_path_name');
    $source_path         = $arguments['source_path'];
    $file_mode = $options['file_mode'];
    if($root_directory_path)
    {
      fwrite(STDOUT, "\n    Will use output directory specified in app.yml: $root_directory_path\n");
    }
    else
    {
      $root_directory_path = sfConfig::get('sf_upload_dir');
      if($root_directory_path)
      {
        fwrite(STDOUT, "\n    No output directory specified in app.yml, will use $root_directory_path\n");
      }
      else
      {
        $error_message = <<<EOF
You have to specify a directory to store your uploaded files in. In apps/{your_app}/config/app.yml, write:
all:
  sf_filebase_plugin:
    path_name: /home/joshi/www/test/web/uploads/assets
EOF;
        throw new Exception($error_message);
      }
    }

    $filebase = sfConfig::get('sf_public_filebase');
    if(!$filebase->fileExists() || !$filebase->isWritable())
    {
      throw new Exception(sprintf('Filebase root directory %s does not exist or is write protected. Ensure that the directory exists in file system and that it is writable by the running php processes/apache users.', $filebase->getPathname()));
    }
    
    // initialize the database connection
    sfContext::createInstance($this->configuration);
    $databaseManager = sfContext::getInstance()->getDatabaseManager();
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $root = Doctrine::getTable('sfFilebaseDirectory')->getRootNode($options['env'], $arguments['application']);
    if($root->isNew())
    {
      throw new Exception('Filebase database was not initialized yet, execute sfFilebase:create-root task for doing this.');
    }
    else
    {
      $source = $filebase->getFilebaseFile($source_path);
      if(!$source->fileExists() || !$source->isReadable() || !$source instanceof sfFilebasePluginDirectory)
      {
        throw new Exception(sprintf("Source directory % s does not exist, is not readable or is even not a directory.", $source->getPathname()));
      }
      fwrite(STDOUT, "\n    Start synchronizing directory $source_path\n");

      function recurse(sfFilebasePluginFile $source, sfFilebaseDirectory $parent_dir, $file_mode)
      {
        try
        {
          foreach($source AS $file)
          {
            if($file instanceof sfFilebasePluginDirectory)
            {
              fwrite(STDOUT, sprintf("\n    Creating directory %s in %s\n", $source->getFilename(), $parent_dir->getFilename()));
              $node = new sfFilebaseDirectory();
              $hash = md5(uniqid(rand(), true));
              $node->setHash($hash);
              $node->setFilename($file->getFilename());
              $node->save();
              $node->getNode()->insertAsLastChildOf($parent_dir);
              recurse($file, $node, $file_mode);
            }
            else
            {
              fwrite(STDOUT, sprintf("\n    Copying %s to %s\n", $source->getPathname(), $parent_dir->getFilename()));
              $copy = $file->copy($source->getFilebase()->getPathname(), true);
              $hash = $hash = md5(uniqid(rand(), true)) . '.' . $copy->getExtension();
              $node = new sfFilebaseFile();
              $node->setFilename($copy->getFilename());
              $node->setHash($hash);
              $node->save();
              $node->getNode()->insertAsLastChildOf($parent_dir);
              $move = $copy->move($hash);
              $move->chmod($file_mode);
            }
          }
        }
        catch(Exception $e)
        {
          throw new Exception ((string) $e);
        }
      }

      recurse($source, $root, $file_mode);
    }
    fwrite(STDOUT, "\n    Import completed.\n");
    fwrite(STDOUT, "\n");
  }
}
