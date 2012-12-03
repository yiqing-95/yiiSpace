<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-3
 * Time: 上午1:12
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------------
 * 系统穿越性功能 在这里分流
 * --------------------------------------------------------------
 */
class SysController extends YsController
{
    public function actionStarRatingAjax() {

       $ratingAjax=isset($_POST['rate']) ? $_POST['rate'] : 0;
       echo "You are voting $ratingAjax through AJAX!";
   }

}
