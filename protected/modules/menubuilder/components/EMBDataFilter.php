<?php
/**
 * EMBDataFilter.php
 *
 * The class for filtering menu/items data.
 * Installed as dataFilterClass in the module
 *
 * This class handles the current and corresponding supported userroles:
 * 'guest' and 'authenticated'
 * Override this to implement your own roles
 * @see EMBRbacDataFilter
 *
 * Example: Set 'dataFilterClass'=>'MyDataFilter' in the modules config in config/main.php
 *
        class MyDataFilter extends EMBDataFilter
        {
            public static function getCurrentUserRoles()
            {
                switch(Yii::app()->user->id)
                {
                    case 'admin':
                    $roles = array('authenticated'=>'Authenticated user','admin'=>'Admin');
                    break;

                    case 'sitemaster':
                    $roles = array('authenticated'=>'Authenticated user','sitemaster'=>'Sitemaster');
                    break;
                    ...
                    default:
                    $roles = parent::getCurrentUserRoles(); //authenticated or guest or configured in config/main.php
                }

                return $roles;
            }

            public static function getSupportedUserRoles()
            {
                return array_merge(parent::getSupportedUserRoles(),array('admin'=>'Admin','sitemaster'=>'Sitemaster'));
            }
        }
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBDataFilter
{

    /**
     * Return the current user roles
     *
     * @return array
     */
    public static function getCurrentUserRoles()
    {
        if(Yii::app()->user->isGuest)
            return array('guest'=>Yii::t('MenubuilderModule.messages','Guest'));
        else
            return array('authenticated'=>Yii::t('MenubuilderModule.messages','Authenticated user'));
    }

    /**
     * Return all available user roles
     *
     * @return array
     */
    public static function getSupportedUserRoles()
    {
        return array('guest' => 'Guest', 'authenticated' => 'Authenticated');
    }


    /**
     * Check access to a menu / menuitem model
     *
     * @param $model
     * @param $adminMode
     * @param null $userRoles
     * @param bool $isMenu
     * @return bool
     */
    public static function checkRoles($model,$adminMode,$userRoles=null,$isMenu=false)
    {
        if(empty($model))
            return false;

        if($adminMode && !$isMenu)
            return true; //allow access to all menuitems in adminmode

       if(!isset($userRoles))
         $userRoles = array_keys(static::getCurrentUserRoles()); //allow subclasses to override getCurrentUserRole

        if(empty($userRoles))
            return $adminMode; //false if not adminMode and user has no roles assigned


        if($adminMode)
            $checkModelRoles=!empty($model->adminroles) ? explode(',',$model->adminroles) : array();
        else
            $checkModelRoles=!empty($model->userroles) ? explode(',',$model->userroles) : array();

        if(empty($checkModelRoles))
            return true;

        if(is_string($userRoles))
            $userRoles = array($userRoles);

        $intersect = array_intersect($checkModelRoles,$userRoles);
        return empty($intersect) ? false : true;
    }

    /**
     * Check access to menu / menuitem model for the specified scenario
     *
     * @param $model
     * @param $scenarios
     * @return bool
     */
    public static function checkScenarios($model,$scenarios)
    {
        if(!empty($scenarios))
        {
            $modelScenarios=!empty($model->scenarios) ? explode(',',$model->scenarios) : array();
            if(empty($modelScenarios))
                return true;

            if(is_string($scenarios))
                $scenarios = array($scenarios);

            $intersect = array_intersect($modelScenarios,$scenarios);
            return empty($intersect) ? false : true;
        }
        else
            return true;
    }
}
