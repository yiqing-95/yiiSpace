<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminUserIdentity extends CUserIdentity {
	const ERROR_STATE_INVALID=11;

	private $_id;
	private $_user;

	public function authenticate() {
		$user = AdminUser::model()->findByAttributes(array('username' => $this->username));
		if($user === NULL)
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else if($user->password !== AdminUser::hashPassword($this->password,$user->encrypt))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else if($user->disabled == 1)
			$this->errorCode = self::ERROR_STATE_INVALID;
		else {
			$this->_id = $user->id;
			$this->_user = $user;

			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	public function getId() {
		return $this->_id;
	}

	public function getUser() {
		return $this->_user;
	}



}