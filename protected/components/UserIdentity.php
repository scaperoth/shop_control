<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;
    private $email;
    private $role;
    private $member;

    public function authenticate() {
        $options = Yii::app()->params['ldap'];
        $dc_string = "dc=" . implode(",dc=", $options['dc']);
        $ldapHost = $options['host'];
        $ldapPort = $options['port'];
        $ldapDn = $this->username . $options['domain'];

        /**/
        $baseDnPeople = 'ou=' . $options['oupeople'] . ',' . $dc_string;
        $baseDnStaff = 'ou=' . $options['oustaff'] . ',' . $dc_string;
        $baseDnTest = 'ou=' . $options['outest'] . ',' . $dc_string;

        $filter = "(|(samAccountName=$this->username))";
        $attr = array("memberof", "cn", "mail");

        $adminGroups = array("AT-DISPATCH-ADMIN");
        $userGroups = array("AT-DISPATCH-USER");
        $checkItgroup = array("AT-INF");
        $checkAVOgroup = array("AT-AVO");
        $checkSCusergroup = array("AT-SC");
        $checkSCAdmingroup = array("ATSC-SA");
        $checkADVroup = array("AT-AVD");

        $ldapConn = ldap_connect($ldapHost, $ldapPort);  // must be a valid LDAP server!
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything

        if ($ldapConn) {

            @$ldapBind = ldap_bind($ldapConn, $ldapDn, $this->password); //can use either dn(distinguished name) or rdn(relative dn) 

            if ($ldapBind) {
                $this->_id = 1;

                $sr = ldap_search($ldapConn, $baseDnPeople, $filter, $attr);
                $info = ldap_get_entries($ldapConn, $sr);

                for ($i = 0; $i < $info["count"]; $i++) {
                    $this->email = $info[$i]["mail"][0];
                    $userFullName = $info[$i]["cn"][0];
                }
                $num = ldap_count_entries($ldapConn, $sr);

                if ($num == 0) {
                    $sr = ldap_search($ldapConn, $baseDnStaff, $filter, $attr);
                    $info = ldap_get_entries($ldapConn, $sr);

                    for ($i = 0; $i < $info["count"]; $i++) {
                        $this->email = $info[$i]["mail"][0];
                        $userFullName = $info[$i]["cn"][0];
                    }
                    $num = ldap_count_entries($ldapConn, $sr);
                }

                //test accounts
                if ($num == 0) {
                    $sr = ldap_search($ldapConn, $baseDnTest, $filter, $attr);
                    $info = ldap_get_entries($ldapConn, $sr);

                    for ($i = 0; $i < $info["count"]; $i++) {
                        //$this->email= $info[$i]["mail"][0];
                        $userFullName = $info[$i]["cn"][0];
                    }
                    $num = ldap_count_entries($ldapConn, $sr);
                }

                $firstEntry = ldap_first_entry($ldapConn, $sr);

                $memberOf = ldap_get_values($ldapConn, $firstEntry, "memberof");

                $cn = ldap_get_values($ldapConn, $firstEntry, "cn");
//				$username = $cn[0];
                
                foreach ($memberOf as $key => $value) {

                    $membership = explode(",", $value);

                    $member = explode("=", $membership[0]);

                    $member_group = ((isset($member[1])) ? $member[1] : $member[0]);

                    if (in_array($member_group, $adminGroups)) {

                        $this->setState('role', 'admin');
                    }if (in_array($member_group, $userGroups)) {

                        $this->setState('role', 'user');
                    }
                    if (in_array($member_group, $checkItgroup)) {
                        $ADvalue = 'AT-INF';
                    }

                    if (in_array($member_group, $checkAVOgroup)) {

                        $ADvalue = 'AT-AVO';
                    }
                    if (in_array($member_group, $checkSCusergroup)) {
                        
                        $ADvalue = 'AT-SC';
                    }
                    if (in_array($member_group, $checkSCAdmingroup)) {

                        $ADvalue = 'ATSC-SA';
                    }
                    if (in_array($member_group, $checkADVroup)) {

                        $ADvalue = 'AT-AVD';
                    }
                }
                Yii::app()->user->setState('username', $userFullName);
                $this->errorCode = self::ERROR_NONE;
            }
            if (!$ldapBind) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
        }
        return !$this->errorCode;
    }

    public function getId() {
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