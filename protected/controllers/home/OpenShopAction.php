<?php

class CronJobAction extends CAction {

    public function run() {
        $admin_emails = Yii::app()->params['admin_emails'];    
        //check if "action" == md5(go);
        if ($_GET['action'] == md5('go')) {
            $holidays_query = Yii::app()->db->createCommand()
                    ->select('sh.hol_id, loc_id, hol_date')
                    ->from('shop_holidays sh, Holidays h')
                    ->where('sh.hol_id = h.hol_id')
                    ->queryAll();


            $locations = Locations::model()->findAll();
            foreach ($locations as $curr_location) {
                $location = $curr_location['loc_name'];
                $isopen = Locations::model()->findByAttributes(array('loc_name' => $location), 'loc_status');
                $shouldbeopen = FALSE;
                $isholiday = FALSE;
                echo '<h3>' . $location . '</h3>';
                if (!$isopen) {
                    echo'<pre class="well">closed</pre>';
                }else{
                    echo'<pre>open</pre>';
                }

                $dayofweek = strtolower(date('D'));
                $table_col_open = 'loc_' . $dayofweek . '_open_hrs';
                $table_col_closed = 'loc_' . $dayofweek . '_closed_hrs';
//check open hours
                $check_query = Yii::app()->db->createCommand()
                        ->select($table_col_open . ',' . $table_col_closed)
                        ->from('locations l')
                        ->where('l.loc_name=:name', array(':name' => $location))
                        ->queryRow();
                $openHrsTime = strtotime($check_query[$table_col_open]);
                $closedHrsTime = strtotime($check_query[$table_col_closed]);

                $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
                $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

                $closed_upper_bound = date('d-m-Y H:i:s', $closedHrsTime + 600);
                $closed_lower_bound = date('d-m-Y H:i:s', $closedHrsTime - 600);

                $currtime = date('d-m-Y H:i:s');
                $currDay = date('M d');


                foreach ($holidays_query as $holiday) {
                    if ($holiday['hol_date'] == $currDay) {
                        if ($holiday['loc_id'] == $curr_location['loc_id']) {
                            echo "Don't worry! it's a holiday";
                            $isholiday = TRUE;
                        }
                    }
                }

                if ($currtime < $closed_lower_bound && $currtime > $open_upper_bound) {
                    $shouldbeopen = TRUE;
                }
                $to = $admin_emails;
                $subject = 'Test email using PHP';
                $headers = "From: Shop Control App acadtech@gwu.edu";

                $subject= NULL;
                /**EMAIL LOGIC FOR CRON JOB**/
                if (!$isholiday) {
                    //it"s not a holiday...
                    if (!$isopen) {
                        //it's not open
                        $subject= NULL;
                        if ($shouldbeopen) {
                            //it should be open
                            $subject="$location shop has not been opened @ $currtime";
                            $message = "$location should have been opened by $open_upper_bound. Not yet opened.";
                        } else {
                            //but that's ok
                            $subject=NULL;
                        }
                    }else{
                        //it's open
                        if($shouldbeopen){
                            //but it should be, and that's ok
                           $subject= NULL;
                        }else{
                            //this means no one has closed it
                            $subject="$location, has not been closed @ $currtime";
                            $message = "$location should have been closed by $closed_lower_bound. Not yet closed.";
                        }
                    }
                }
                if($subject)
                    mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');
                
            }
        }
    }

}

?>
