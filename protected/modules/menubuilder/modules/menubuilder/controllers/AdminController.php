<?php
/**
 * AdminController
 *
 * Copy the 'menubuilder' module to protected/modules
 * This is your working copy where you can override and extend the menubuilder
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class AdminController extends EMBAdminController
{

    //override or add actions here
    public function filters()
    {
        return array('accessControl'); // perform access control for CRUD operations
    }

    //change this to secure rules
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to access all actions
                'users' => array('*'),
            ),
            array('deny'),
        );
    }


    public function formPermissionRules()
    {
        return array(
            array('allow', // allow all users to access all form fields and actions
                'users' => array('*'),
               // 'util' => array('flushcache','import','export'),
               // 'menuitem'=>array('create','update','delete'),
            ),
            array('deny'),
        );
    }





}