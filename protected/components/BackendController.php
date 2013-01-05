<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-10
 * Time: 下午12:16
 * To change this template use File | Settings | File Templates.
 */
class BackendController extends Controller
{
    public $layout = '//layouts/iframe';

   // public $layout = '//adminLayouts/main';

    public $menu = array();

    public $breadcrumbs = array();

    public $menuLabelList = array(); //该菜单的显示名级联，含父级菜单显示名

    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        //session中存入一个标识字段 表明是在后台
        Yii::app()->user->setState('is_admin_action', true);
    }


    public function init()
    {
        if (isset($_GET['menuId'])) {



            $currentMenuNode = AdminMenu::model()->findByPk($_GET['menuId']);

            $ancestors = $currentMenuNode->ancestors()->findAll();
            // var_dump($ancestors) ; die(__METHOD__) ;
            while (($menuNode = array_shift($ancestors)) !== null) {
                    //顶级虚根 没有关联菜单
                   if($menuNode->group_code != 'sys_admin_menu_root'){
                       $this->menuLabelList[$menuNode->label] = $menuNode->calcUrl();
                       if($menuNode->level == 2){
                           user()->setState('activeTopMenuId',$menuNode->id);
                       }
                   }
            }
            $this->menuLabelList[$currentMenuNode->label] = $currentMenuNode->calcUrl();
            user()->setState('adminTopMenu',$this->menuLabelList);
        }else{
            if(($menuLabelList = user()->getState('adminTopMenu',false)) !== false){
                $this->menuLabelList = $menuLabelList;
            }
        }
        parent::init();
    }


    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('*'),
                'actions' => array('login'),
            ),
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
}
