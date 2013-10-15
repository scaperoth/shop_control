<?php

class OpenShopAction extends CAction {

    public function run() {

        if (Yii::app()->request->isPostRequest) {
//administrative emails
            $admin_emails = Yii::app()->params['admin_emails'];
            echo $admin_emails . ' : ';
//full username
            $username = Yii::app()->user->getState('username');
//open or closed
            $action = 'open';
//get timestamp
            $timestamp = date('Y-m-d H:i:s');

//is user ontime?
            $on_time = 0;

//whether or not to send an email defaults to no
            $message = NULL;

//get curr location
            $location = $this->getController()->location;
//get current day of the week
            $dayofweek = strtolower(date('D'));
            $table_col = 'loc_' . $dayofweek . '_open_hrs';
//check open hours
            $check_query = Yii::app()->db->createCommand()
                    ->select($table_col)
                    ->from('locations l')
                    ->where('l.loc_name=:name', array(':name' => $location))
                    ->queryRow();

//translate databse open hours into php time()
            $openHrsTime = strtotime($check_query[$table_col]);

//get current time
            $currtime = date('d-m-Y H:i:s');

//get upper deviation of time. +- 10 minutes from db shop open time
            $open_upper_bound = date('d-m-Y H:i:s', $openHrsTime + 600);
            $open_lower_bound = date('d-m-Y H:i:s', $openHrsTime - 600);

//if current time is later than the open + 10 minutes
//shop was opened late
            if ($currtime > $open_upper_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_upper_bound);
                $difference = $currtime->diff($openDateTime, True);

                $message = $username . " opened the $location shop late. Latest opening time is " . date('H:ia', $openHrsTime + 600) . '. Late by: ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';
                Yii::app()->user->setFlash('error', "The shop has been opened late by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            }

//if current time is less than the open - 10 minutes
//shop was opened early
            else if ($currtime < $open_lower_bound) {
                $currtime = new DateTime($currtime);
                $openDateTime = new DateTime($open_lower_bound);
                $difference = $currtime->diff($openDateTime, True);


                $message = $username . " opened the. $location shop early by " . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds';

                Yii::app()->user->setFlash('error', 'The shop has been opened early by ' . $difference->h . ' hours ' . $difference->i . ' minutes and ' . $difference->s . ' seconds');
            } else {
                $on_time = 1;
                $message = NULL;
            }

//if send messages is set to 1 then send the email. otherwise don't.
            if ($message != NULL && !Yii::app()->user->checkAccess('admin')) {


                $to = $admin_emails;
                $subject = 'Test email using PHP';
                $headers = 'From: acadtech@gwu.edu' . "\r\n" .
                        'Reply-To: scaperoth@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $message, $headers, '-facadtech.gwu.edu');

                $lct = new LocationChangeTracking();
                $lct->lct_location = $location;
                $lct->lct_user = $username;
                $lct->lct_action = $action;
                $lct->lct_on_time = $on_time;
                $lct->lct_message = $message;
                $lct->lct_timestamp = $timestamp;
                $lct->insert();
            }

            $loc_id = $this->getController()->loc_id;
            $new_status = 1;
            $location_model = Locations::model()->findByPk($loc_id);
            $location_model->loc_status = $new_status;
            $location_model->save(); // save the change to database


            echo $message;
        } else {
            Yii::app()->user->setFlash('error', 'Access Denied.');
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

}

?>
