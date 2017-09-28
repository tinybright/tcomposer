<?php

class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$password=Yii::app()->getController()->getModule()->password;
		if($password===null)
			throw new CException('Please configure the "password" property of the "gen" module.');
		elseif($password===false || $password===$this->password)
			$this->errorCode=self::ERROR_NONE;
        else
            $this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
		$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
}