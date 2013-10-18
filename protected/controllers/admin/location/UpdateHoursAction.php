<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UpdateHoursAction extends CAction {

    public function run() {
            $db_rows = Locations::model()->findAll();
            $days_of_week = array(
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            );


            $rows_to_update = explode(',', $_POST['rows']);

            foreach ($db_rows as $row) {
                $curr_id = $row['loc_id'];
                foreach ($rows_to_update as $update_row) {
                    if (md5($curr_id) == $update_row) {

//select row to update
                        $update = Locations::model()->findByPk($curr_id);
                        $insert = new LocationHoursTracking();

                        $username = Yii::app()->user->getState('username');

                        $insert->lht_username = $username;
                        $insert->lht_loc_name = $row['loc_name'];

                        foreach ($days_of_week as $day) {

//get short representation of day
                            $short_day = substr($day, 0, 3);

//set string EX: 'mon_open_hrs'
                            $open_hrs = $short_day . '_open_hrs';
                            $closed_hrs = $short_day . '_closed_hrs';

//convert table value to date()
                            $open_hours_string = strtotime($_POST[$row['loc_name'] . '|' . $open_hrs]);
                            $open_hours_to_insert = date('H:i:s', $open_hours_string);

//convert table value to date()
                            $closed_hours_string = strtotime($_POST[$row['loc_name'] . '|' . $closed_hrs]);
                            $closed_hours_to_insert = date('H:i:s', $closed_hours_string);

//set string for column, for example 'loc_mon_open_hrs'
                            $update_open_col = 'loc_' . $open_hrs;
                            $update_closed_col = 'loc_' . $closed_hrs;
                            $insert_open_col = 'lht_' . $open_hrs;
                            $insert_closed_col = 'lht_' . $closed_hrs;
//update column
                            $update->$update_open_col = $open_hours_to_insert;
                            $update->$update_closed_col = $closed_hours_to_insert;

//update rows in tracking table
                            $insert->$insert_open_col = $open_hours_to_insert;
                            $insert->$insert_closed_col = $closed_hours_to_insert;
                        }
//get timestamp and format for db
                        $now = $createdate = date('Y-m-d H:i:s');
                        $insert->lht_timestamp = $now;

//save insert
                        $insert->save();
//save update
                        $update->save();
                        Yii::app()->user->setFlash('time_update', 'Shop times have been updated.');
                    }
                }
            }
        
    }

}

?>
