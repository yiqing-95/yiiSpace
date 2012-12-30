<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-25
 * Time: 上午10:08
 * To change this template use File | Settings | File Templates.
 */
class FriendPrivacyHandler extends PrivacyHandler
{

    public function  handle()
    {
        echo __METHOD__ , PHP_EOL,'<br/>';
        // meet some condition then pass the controller to the next successor!
        if(true){
            if(!empty($this->successor)){
                $this->successor->handle();
            }

        }
        $this->isHandled = true ;
    }
}
