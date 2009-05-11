<?php
class CreateDirectoryForm extends sfForm
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
      'name' => new sfWidgetFormInput(),
      'directory' => new sfWidgetFormChoice(array('choices'=>$directories))
    ));

    $this->setValidators(array(
      'name' => new sfValidatorAnd(array(
          new sfValidatorString(array('required'=>true)),
          new sfValidatorRegex(array('pattern'=>'#^[^\.].+?$#'), array('invalid' => 'No dots allowed at the beginning of a name.'))
       )),
      'directory' => new sfValidatorChoice(array('choices'=>array_keys($directories), 'required' => false))
    ));

    $this->widgetSchema->setNameFormat('directory[%s]');
  }
}