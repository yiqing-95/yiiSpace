<?php
/**
 * @Desc('this is a test class . you can use test/help to see all available test items')
 */
class YqController extends Controller
{
  public function actionTestPdo(){
      $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'));


      $criteria = $topRoot->descendants()->getDbCriteria();
      $criteria->select .= ', label as name'; //ztree 用 name 作为显示！
      $command = $topRoot->getCommandBuilder()->createFindCommand($topRoot->getTableSchema(), $criteria);
      //$command->setFetchMode(PDO::FETCH_KEY_PAIR);
     // $command->setFetchMode(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
      $command->setFetchMode(PDO::FETCH_GROUP);
      $descendants = $command->queryAll();
      /*
      // $roots = SysMenuTree::model()->roots()->with('menu')->findAll();
      $roots = $topRoot->children()->findAll(array('index'=>'id'));
      */
      print_r($descendants);

  }
}