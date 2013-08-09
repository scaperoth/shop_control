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
        private $role;
	public function authenticate()
	{
            $options = Yii::app()->params['ldap'];
            $dc_string = "dc=" . implode(",dc=",$options['dc']);
            $ldapHost = $options['host'];
            $ldapPort = $options['port'];
            $ldapDn = $this->username.$options['domain'];
	 	
            /*NOT NECESSARY?
            $baseDnPeople = $options['oustaff'].','.$dc_string;
            $baseDnStaff = $options['oupeople'].','.$dc_string;

            $filter = "(|(samAccountName=$this->username))";
            $attr = array("memberof","cn","mail");
            */
            $ldapConn=ldap_connect($ldapHost, $ldapPort);  // must be a valid LDAP server!

            //ldap_set_option( $ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3 );  //Set the LDAP Protocol used by your AD service
            ldap_set_option( $ldapConn, LDAP_OPT_REFERRALS, 0 );         //This was necessary for my AD to do anything

            if($ldapConn){
                echo ldap_error($ldapConn);
                
                $ldapBind=ldap_bind($ldapConn, $ldapDn, $this->password);//can use either dn(distinguished name) or rdn(relative dn) 
                
                if($ldapBind){
                    echo 'connected';
            
                    $this->_id=1;
                    $this->role='admin';

                    $this->setState('role', 'user');

                    //TODO Figure this crap out...
                    $auth=Yii::app()->authManager; //initializes the authManager*/
                    if(!$auth->isAssigned($this->role,$this->_id))//checks if the role for this user has already been assigned and if it is NOT than it returns true and continues with assigning it below
                    {
                        if($auth->assign($this->role,$this->_id))//assigns the role to the user
                        {
                            Yii::app()->authManager->save();//saves the above declaration
                        }
                    }
                    $this->errorCode=self::ERROR_NONE;
                }
                
                return !$this->errorCode;
            }
        }
        public function getId()
        {
            return $this->_id;
        }
        
}

/* OLD AUTH 
 * $record=User::model()->findByAttributes(array('username'=>$this->username));
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
                }
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
        }

        public function getId()
        {
            return $this->_id;
        }
 */