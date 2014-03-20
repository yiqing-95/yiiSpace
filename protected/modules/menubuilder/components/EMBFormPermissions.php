<?php
/**
 * EMBFormPermissions.php
 *
 * Configure the permissions of the adminform analogous to the accessRules of a CController
 * The EMBFormPermissions class is built analogous to the CAccessControlFilter.
 * @link http://www.yiiframework.com/doc/api/1.1/CAccessControlFilter
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBFormPermissions extends CComponent
{
    /**
     * All available permissions
     * Use these keys instead of the 'actions' key of the accessRules of a CController
     * Set menu=>false, menuitem=>false, util=>false for no access to these edit forms.
     *
     * @var array
     */
    public $permissions = array(
        'menu'=>array('create','update','delete','simulate','preview'),
        'menuitem'=>array('create','update','delete','arrange','advanced'),
        'util'=>array('flushcache','restoredefault','saveasdefault','reinstall','import','export'),
        'menuFields'=>array('menuid','visible','locked','sortposition','maxdepth','icon','titles','descriptions','scenarios','userroles','adminroles','createdinfo'),
        'menuitemFields'=>array('visible','active','labels','descriptions','url','target','ajaxOptions','linkOptions','ajaxOptions','itemOptions','submenuOptions','template','icon','scenarios','userroles','createdinfo'),
        'simulateFields'=>array('scenarios','userroles','languages'),
     );

    /**
     * Predefined rules for creating menu only: used on install
     * @var array
     */
    public $createMenuOnlyPermissions = array(
        'menu'=>array('create'),
        'menuFields'=>array('menuid','visible','title','scenarios','userroles','adminroles'),
        'menuitem'=>false,
    );

    /**
     * Internal rules
     * @var array
     */
    private $_rules=array();

    /**
     * @return array list of access rules.
     */
    public function getRules()
    {
        return $this->_rules;
    }

    /**
     * Similar to CAccessControlFilter::setRules
     *
     * @param $rules
     */
    public function setRules($rules)
    {
        foreach($rules as $rule)
        {
            if(is_array($rule) && isset($rule[0]))
            {
                $r=new EMBFormPermissionsRule;
                $r->allow=$rule[0]==='allow';
                foreach(array_slice($rule,1) as $name=>$value)
                {
                    if(($name==='menu' || $name==='menuitem' || $name==='util') && $value===false)
                        $r->$name=$value;
                    else
                    if($name==='expression' || $name==='roles')
                        $r->$name=$value;
                    else
                        $r->$name=array_map('strtolower',$value);
                }
                $this->_rules[]=$r;
            }
        }
    }

    /**
     * Check if an action of a context (menu, menuitem, util, simulate) is allowed
     *
     * @param $context
     * @param $permission
     * @return bool
     */
    public function isAllowed($context,$permission)
    {
        if(!array_key_exists($context,$this->permissions))
            return false;

        if(!in_array($permission,$this->permissions[$context]))
            return false;


        $app=Yii::app();
        $request=$app->getRequest();
        $user=$app->getUser();
        $verb=$request->getRequestType();
        $ip=$request->getUserHostAddress();

        foreach($this->getRules() as $rule)
        {
            if(($allow=$rule->isAllowed($user,$context,$permission,$ip,$verb))>0) // allowed
                break;
            else if($allow<0) // denied
            {
                return false;
            }
        }

        return true;
    }

}


/**
 * EMBFormPermissionsRule represents an access rule
 */
class EMBFormPermissionsRule extends CAccessRule
{
    public $menu;
    public $menuFields;
    public $menuitem;
    public $menuitemFields;
    public $util;

    /**
     * Check allowed
     *
     * @param $user
     * @param $context
     * @param $permission
     * @param $ip
     * @param $verb
     * @return int
     */
    public function isAllowed($user,$context,$permission,$ip,$verb)
    {
        if($this->isPermissionMatched($context,$permission)
            && $this->isUserMatched($user)
            && $this->isRoleMatched($user)
            && $this->isIpMatched($ip)
            && $this->isVerbMatched($verb)
            && $this->isExpressionMatched($user))
            return $this->allow ? 1 : -1;
        else
            return 0;
    }

    /**
     * Check if a permission is matched
     * Overrides the method from CAccessRule
     *
     * @param $context
     * @param $permission
     * @return bool
     */
    protected function isPermissionMatched($context,$permission)
    {
        if(!isset($this->$context))
            return true;
        else
        if($this->$context === false || !is_array($this->$context) || empty($this->$context))
            return false;

        return in_array(strtolower($permission),$this->$context);
    }


}
