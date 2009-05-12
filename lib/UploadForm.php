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
    
    $files = new sfWidgetFormSchema();
    $validator_files = new sfValidatorSchema();
    
    $files['0'] = new sfWidgetFormInputFile();
    $files['1'] = new sfWidgetFormInputFile();

    $validator_files['0'] = new sfFilebasePluginValidatorFile(array('required'=>true));
    $validator_files['1'] = new sfFilebasePluginValidatorFile(array('required'=>true));

    $this->setWidgets(array(
      'files' => $files,
      'directory' => new sfWidgetFormChoice(array('choices'=>$directories))
    ));

    $this->setValidators(array(
      'files' => $validator_files,
      'directory' => new sfValidatorChoice(array('choices'=>array_keys($directories), 'required' => false))
    ));

    $this->widgetSchema->setNameFormat('upload[%s]');
  }
}