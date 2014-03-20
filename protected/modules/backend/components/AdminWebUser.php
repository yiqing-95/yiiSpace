<?php

/**
 * 后台管理 USER类
 *
 * @author 吴涛
 */
class AdminWebUser extends CWebUser {

	/**
	 * 禁用自动登录
	 * @var bool
	 */
	public $allowAutoLogin = false;
	private $userModel;

	/**
	 * 是否是超级管理员
	 * @var bool
	 */
	public $isSuperUser = false;

	/**
	 * 登录地址
	 * @var array
	 */
	public $loginUrl = array('/site/login');

	public function __get($name) {
		if (!parent::getIsGuest()) {
			if (!$this->hasState('__userInfo')){
                $userModel = $this->getUserModel() ;
                $this->setState('__userInfo', empty($userModel)?array():$userModel->getAttributes());
            }

			$user = $this->getState('__userInfo', array());

			if (isset($user[$name])) {
				return $user[$name];
			}
		}

		return parent::__get($name);
	}

	/**
	 *  用户登录
     * @param AdminUserIdentity $identity
     * @param int $duration
     * @return bool|void
     */
    public function login( $identity, $duration = 0) {
		parent::login($identity, $duration);
		$this->userModel = $identity->getUser();
		$this->updateUserInfo();
	}

	/**
	 * 获取用户对象
	 * @return AdminWebUser
	 */
	public static function getUser() {
		return Yii::app()->getUser();
	}

	/**
	 * 获取用户对象
	 * @return AdminUser|null
	 */
	public function getUserModel() {
		if ($this->userModel == null && $this->getId() !== null) {
			$this->userModel = AdminUser::model()->with('role')->findByPk($this->getId());
		}
		return $this->userModel;
	}

	/**
	 * 更新用户信息
	 */
	public function updateUserInfo() {
		if ($this->isGuest)
			return false;
		//$userInfo = $this->getUserModel()->attributes;
		//$userInfo['role'] = $this->getUserModel()->role->name;
		//$this->setState('__userInfo', $userInfo);
	}

	/**
	 * 获取菜单信息
	 * @param string $val （m获取菜单 c获取菜单控制） 列表
	 */
	public function getMenus($val='m') {

		if($this->isSuperUser) {

			return Yii::app()->getDb()->createCommand()
					->from(AdminMenu::model()->tableName())
					->where('display=1')
					->order('listorder DESC')
					->queryAll();
		} else {
			$menus = $this->getUserModel()->role->getMenus();
			
			if($val=='m')
				return $menus['menus'];
			else
				return $menus['controllers'];
		}
	}


}

?>
