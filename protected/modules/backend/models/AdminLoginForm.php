<?php
Yii::app()->getModule('backend');

class AdminLoginForm extends CFormModel {

	public $username;
	public $password;
    public $verifyCode;

	/**
	 *
	 * @var AdminIdentity
	 */
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules() {
		return array(
			array('username, password', 'required'),
			array('password', 'authenticate'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels() {
		return array(
			'username' => Yii::t('BackendModule.models/AdminLoginForm', 'User Name'),
			'password' => Yii::t('BackendModule.models/AdminLoginForm', 'Password'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute, $params) {
		if (!$this->hasErrors()) {
			$this->_identity = new AdminUserIdentity($this->username, $this->password);
			if (!$this->_identity->authenticate()) {
				switch ($this->_identity->errorCode) {
					case AdminUserIdentity::ERROR_STATE_INVALID:
						$this->addError('username', Yii::t('BackendModule.models/AdminLoginForm', 'ERROR_STATE_INVALID'));
						break;
					default:
						$this->addError('password',  Yii::t('BackendModule.models/AdminLoginForm', 'ERROR_INVALID'));
						break;
				}
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login() {
		if ($this->_identity === null) {
			$this->_identity = new AdminUserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}

		if ($this->_identity->errorCode === AdminUserIdentity::ERROR_NONE) {
			$duration =  0;

			Yii::app()->user->login($this->_identity, $duration);
			return true;
		} else {
			return false;
		}
	}

}
