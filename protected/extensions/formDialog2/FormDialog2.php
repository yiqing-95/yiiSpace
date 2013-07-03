<?php


Yii::import('zii.widgets.jui.CJuiWidget');

class FormDialog2 extends CJuiWidget
{
	public $link;
	public $options;

    public $dialogOptions = array();

	public function run()
	{
		if (!$this->options['onSuccess'])
			$this->options['onSuccess']='js:function(data, e){alert("Success")}';

		Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(dirname(__FILE__). DIRECTORY_SEPARATOR .'formDialog.js'));

        if(!empty($this->dialogOptions)){
            $this->options['dialogOptions'] = $this->dialogOptions ;
        }
        $options= CJavaScript::encode($this->options);

        $jsCode = <<<INIT
       $("{$this->link}").live("click",function(e){
       e.preventDefault();
          $(this).formDialog({$options});
          return false ;
       });
INIT;


	    Yii::app()->clientScript->registerScript('FormDialog'.$this->link, $jsCode,CClientScript::POS_HEAD);
	}
	
}

