<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:51
 * To change this template use File | Settings | File Templates.
 */
class HomeController extends Controller
{
  public function actionBlock(){
      $this->widget('status.widgets.pageblock.StatusHomeBlock');
  }

}
