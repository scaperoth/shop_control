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
            $record=User::model()->findByAttributes(array('username'=>$this->username));
            if($record===null)
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            //line commented out since passwords are not encrypted yet
            //else if($record->password!==crypt($this->password,$record->password))
            else if($record->password!==$this->password)
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else
            {
                $this->_id=$record->id;
                $this->username = $record->username;
                
                $this->setState('role', $record->role);
                
                //TODO Figure this crap out...
                /*$auth=Yii::app()->authManager; //initializes the authManager
                if(!$auth->isAssigned($record->role,$this->_id))//checks if the role for this user has already been assigned and if it is NOT than it returns true and continues with assigning it below
                {
                    if($auth->assign($record->role,$this->_id))//assigns the role to the user
                    {
                        Yii::app()->authManager->save();//saves the above declaration
                    }
                }*/
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
        }

        public function getId()
        {
            return $this->_id;
        }
}