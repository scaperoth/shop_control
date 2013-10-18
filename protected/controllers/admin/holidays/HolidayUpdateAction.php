<?php

class HolidayUpdateAction extends CAction {

    public function run() {
    //get array of holiday ids for each holiday selected
        $holiday_ids = $_POST['holidays'];
        //get array of locations for each holiday
        $location_ids = $_POST['locations'];
        //get all records from the holidays table
        $hol_query = Holidays::model()->findAll();
        //get all records from the locations table
        $loc_query = Locations::model()->findAll();

        //remove all shop holidays
        ShopHolidays::model()->deleteAll();

        /**
         * counter for location ids since each holiday may have more than 1
         */
        $counter = 0;
        //select each holiday id from the table: id
        foreach ($holiday_ids as $holiday) {
            //select each holiday from the database
            foreach ($hol_query as $db_hol) {
                //compare the id from the table with that from the database
                if (md5($db_hol['hol_id']) == $holiday) {

                    //make sure there is a location in the counter
                    if ($location_ids[$counter]) {
                        //for each location selected at each holiday: l
                        foreach ($location_ids[$counter] as $loc) {
                            //for each location in the database: db_l
                            foreach ($loc_query as $db_loc) {
                                //compare l with the db_l and if it's a match, update the database
                                if (md5($db_loc['loc_id']) == $loc) {
                                    $sh = new ShopHolidays; //create new record
                                    $sh->hol_id = $db_hol['hol_id']; //add holiday id
                                    $sh->loc_id = $db_loc['loc_id']; //add location id
                                    $sh->save(); //save the state and insert the new row
                                }//end if
                            }//end foreach
                        }//end foreach
                    }
                }//end if
            }//end foreach
            $counter++; //increment counter
        }//end of opening foreach
        //set flash message
        Yii::app()->user->setFlash('holiday_update', 'The holiday table has been updated.');
        //end of isset($_post) if statement
    }

}

?>
