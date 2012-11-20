<?php
/**
 * jquery plugin toastmessage 
 * by binkabir@gmail.com
 */
  
  class toastMessageWidget extends CWidget{
      
      
      /**
       *
       * @var string message to display
       */
      public $message; 
      
      
      /**
       *
       * @var type of notification notice, success, error, warning
       */
      public $type='Success'; 
      
      protected $assets; 
      
      
      
      public function init(){
          //publish the assets folder for the toastmessage 
           $this->assets = Yii::app()->assetManager->publish(dirname(__DIR__).DIRECTORY_SEPARATOR.'toastMessage'.DIRECTORY_SEPARATOR.'assets'); 
          
          
          cs()->registerScriptFile($this->assets.'/jquery.toastmessage.js'); 
          cs()->registerCssFile($this->assets.'/css/jquery.toastmessage.css'); 
          
      }
      
      
      
      public function run(){
        cs()->registerScript("toast-message","
                 $().toastmessage('show".$this->type."Toast', '$this->message');"); 
     
      
//      cs()->registerScript('mbn',"
//      
//       $().toastmessage({
//                            text     : ".$this->message.",
//                            sticky   : true,
//                            position : 'middle-right',
//                            type     : ".$this->type.",
//
//                        });");
      
      }
  }
?>
