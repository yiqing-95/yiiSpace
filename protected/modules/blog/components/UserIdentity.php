<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	
	public function authenticate()
	{
		
		$user=User::model()->find('LOWER(username)=?',array(strtolower($this->username)));		
		if(!isset($user->username))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else{
		    $this->_id=$user->id;
            $this->setState('username', $user->username);
            $this->setState('nickname', $user->nickname);
            $user->saveCounters(array('counts'=>1));
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	
	public function getId()
    {
        return $this->_id;
    }
    
}