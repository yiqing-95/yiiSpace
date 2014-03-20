<?php
/**
 *  
 * User: yiqing
 * Date: 13-4-15
 * Time: 下午3:20
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class PhotoPageBox extends CWidget implements IRunnableWidget{

   public function run(){
       $this->render('photoPageBox');
   }


}