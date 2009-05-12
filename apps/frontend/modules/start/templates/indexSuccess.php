<h1>Filebase Sample application</h1>
<h2>Upload a file</h2>
<?php if(isset($error)):?>
  <p><strong><?php echo $error?></strong></p>
<?php endif?>
<?php if(isset($message)):?>
  <p><em><?php echo $message?></em></p>
<?php endif?>
<form action="<?php echo url_for('start/index')?>" method="post" enctype="multipart/form-data">
  <fieldset>
    <legend>Fileupload</legend>
    <p>
      <?php echo $upload_form->renderHiddenFields()?>
      <?php echo $upload_form['files']->renderError()?>
      <?php echo $upload_form['files']->renderLabel('Select file:')?><br />
      <?php echo $upload_form['files']?>
    </p>
    <p>
      <?php echo $upload_form['directory']->renderError()?>
      <?php echo $upload_form['directory']->renderLabel('Upload into folder:')?><br />
      <?php echo $upload_form['directory']?>
    </p>
  </fieldset>
  <p><input type="submit"/></p>
</form>

<h2>Create a directory</h2>
<form action="<?php echo url_for('start/index')?>" method="post" enctype="multipart/form-data">
  <fieldset>
    <legend>New Directory</legend>
    <p>
      <?php echo $create_directory_form->renderHiddenFields()?>
      <?php echo $create_directory_form['name']->renderError()?>
      <?php echo $create_directory_form['name']->renderLabel('Directory name:')?><br />
      <?php echo $create_directory_form['name']?>
    </p>
    <p>
      <?php echo $create_directory_form['directory']->renderError()?>
      <?php echo $create_directory_form['directory']->renderLabel('Create in folder:')?><br />
      <?php echo $create_directory_form['directory']?>
    </p>
  </fieldset>
  <p><input type="submit"/></p>
</form>

<h2>FBFS ;): Filebase Filesystem</h2>
<code>uploads/<br/>
<?php foreach ($iter = new RecursiveIteratorIterator($filebase, RecursiveIteratorIterator::SELF_FIRST) AS $file):?>
<?php if(!$file->isHidden()):?>
<?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $iter->getDepth()+1)?>
<?php if($file instanceof sfFilebasePluginDirectory):?>
<?php echo $file->getFilename()?>/
<?php else:?>
<a href="<?php echo $file->getAbsolutePathFromWebroot()?>">
<?php echo $file->getFilename()?>
</a>
<?php endif?>
<br />
<?php endif?>
<?php endforeach?>
</code>
<h2>Image Gallery</h2>
<?php foreach ($iter = new RecursiveIteratorIterator($filebase, RecursiveIteratorIterator::SELF_FIRST) AS $file):?>
  <?php if($file instanceof sfFilebasePluginImage):?>
    <div style="float:left; width: 130px; overflow: hidden">
      <span style="height: 30px; display: block; font-size:0.6em"><?php echo $file->getAbsolutePathFromWebroot()?>:</span>
      <img style="display:block" src="<?php echo $file->getThumbnail(array('120'))->getAbsolutePathFromWebroot()?>"/>
    </div>
  <?php endif?>
<?php endforeach?>