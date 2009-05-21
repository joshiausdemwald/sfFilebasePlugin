<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <div id="Overall_Wrapper">
      <div id="Header">
        <h1>sfFilebasePlugin / Filemanager</h1>
      </div>
      <div id="Navigation">
        <p>
          <?php echo link_to('filebase directories', 'sf_filebase_directory/index')?> /
          <?php echo link_to('filebase files', 'sf_filebase_file/index')?>
        </p>
      </div>
      <div id="Sidebar">
        <h2>Files</h2>
        <?php include_component('sf_filebase_filedeliverer', 'directoryTree');?>
      </div>
      <div id="Content">
        <?php echo $sf_content ?>
      </div>
    </div>
  </body>
</html>
