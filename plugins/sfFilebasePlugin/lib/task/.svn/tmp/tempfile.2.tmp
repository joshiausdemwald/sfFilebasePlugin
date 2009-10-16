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
class sfFilebaseCreaterootTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name.'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'sfFilebase';
    $this->name             = 'create-root';
    $this->briefDescription = 'Creates the asset root folder and a root entry in the database.';
    $this->detailedDescription = <<<EOF
The [sfFilebase:create-root|INFO] creates the asset root folder and a root entry in the database.
Call it with:
  [php symfony sfFilebase:create-root|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $root_directory_path = sfConfig::get('app_sf_filebase_plugin_path_name');
    
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

    $filebase = sfFilebasePlugin::getInstance();
    if(!$filebase->fileExists() || !$filebase->isWritable())
    {
      throw new Exception(sprintf('Filebase root directory %s does not exist or is write protected. Ensure that the directory exists in file system and that it is writable by the running php processes/apache users.', $filebase->getPathname()));
    }

    // initialize the database connection
    sfContext::createInstance($this->configuration);
    $databaseManager = sfContext::getInstance()->getDatabaseManager();
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $root = Doctrine::getTable('sfFilebaseDirectory')->getRootNode($options['env'], $arguments['application'], null, true);
    $tree_object = Doctrine::getTable('sfFilebaseDirectory')->getTree();
   
    if($root->getRootId())
    {
      fwrite(STDOUT, "\n    Root node already exists in database, nothing to do.\n");
    }
    else
    {
      try
      {
        $root->setFilename($filebase->getFilename());
        $root->setHash($filebase->getHash());
        $root->setApplication($arguments['application']);
        $root->setEnvironment($options['env']);
        $root->save();
        $tree_object->createRoot($root);
        fwrite(STDOUT, sprintf("\n    Creating root node %s.\n", $filebase->getPathname()));
      }
      catch (Exception $e)
      {
        throw new Exception("\n    Error creating root node in database. Check your connection parameters!\n\n$e\n");
      }
    }
    fwrite(STDOUT, "\n    Database initialized, you may now use the filebase backend feature.\n");
    fwrite(STDOUT, "\n");
  }
}
