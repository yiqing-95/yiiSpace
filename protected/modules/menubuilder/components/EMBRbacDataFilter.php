<?php
/**
 * EMBRbacDataFilter.php
 *
 * The class for filtering menu/items data by Yii RBAC / Authmanager
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBRbacDataFilter extends EMBDataFilter
{

    /**
     * Get the roles from the authManager
     *
     * @param $userId
     * @return array
     */
    protected static function _getRoles($userId)
    {
        $roles = array();

        $authRoles = Yii::app()->authManager->getRoles($userId);
        if(!empty($authRoles))
            foreach($authRoles as $role=>$authItem)
                $roles[$role]=$authItem->name;

        return $roles;
    }

    /**
     * Get the current user roles configured in the authManager
     *
     * @return array
     */
    public static function getCurrentUserRoles()
    {
        return self::_getRoles(Yii::app()->user->id);
    }

    /**
     * Get all supported roles configured in the authManager
     *
     * @return array
     */
    public static function getSupportedUserRoles()
    {
        return self::_getRoles(null);
    }

}