<?php use_helper('Form')?>
<?php foreach($images AS $image):?>
  <?php echo image_tag($image->getAbsolutePathFromWebroot())?>
<?php endforeach?>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="hansiwurst"/>
  <!--input type="file" name="heiner[wotz][hans][]"/-->
  <input type="submit"/>
</form>
<?php #echo textarea_tag('hans',null, array('rich'=>true, 'tinymce_options'=>'language: "de"'))?>