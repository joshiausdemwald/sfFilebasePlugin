<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <div id="Overall_Wrapper" class="clearfix">
      <div id="Header">
        <h1>sfFilebasePlugin / Filemanager</h1>
      </div>
      <div id="Navigation">
        <p>
          <?php echo link_to('overview', 'sf_filebase_overview/index')?> /
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
    <div id="Footer">
      <p><strong>sfFilebasePlugin</strong> / Grab this plugin from <a href="http://www.symfony-project.org/plugins/sfFilebasePlugin">http://www.symfony-project.org/plugins/sfFilebasePlugin</a>. / &copy; 2007&ndash;2009 Johannes Heinen &lt;johannes[dot]heinen[at]gmail[dot]com&gt;</p>
      <div id="License"><p>Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:</p>
<p>
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.</p>

<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p></div>
    </div>
  </body>
</html>
