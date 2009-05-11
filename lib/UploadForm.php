<?php
class UploadForm extends sfForm
{
  public function configure()
  {
    $filebase = new sfFilebasePlugin();

    $directories = array();
    $directories[''] = $filebase->getFilename();
    foreach($iter = new RecursiveIteratorIterator($filebase->getIterator(), RecursiveIteratorIterator::SELF_FIRST) AS $dir)
    {
      if($dir instanceof sfFilebasePluginDirectory)
      {
        $directories[$dir->getRelativePathFromFilebaseDirectory()] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $iter->getDepth()+1) . $dir->getFilename();
      }
    }

    $this->setWidgets(array(
      'upload' => new sfWidgetFormInputFile(),
      'directory' => new sfWidgetFormChoice(array('choices'=>$directories))
    ));

    $this->setValidators(array(
      'upload' => new sfValidatorFile(array('required'=>true)),
      'directory' => new sfValidatorChoice(array('choices'=>array_keys($directories), 'required' => false))
    ));

    $this->widgetSchema->setNameFormat('upload[%s]');
  }
}