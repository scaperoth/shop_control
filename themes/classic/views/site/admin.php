<?php
/* @var $this HomeController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>


<div>

    <div id="classroomDataForm" class="admin-form">
        <h3 class="section-title">Shop Hours</h3>
        <dfn class="description">Click times to edit.</dfn>
        <form id="classroomDateForm">
            <fieldset>
                <table id="timeTable" align="center" border="1">
                    <thead>
                    <td>Location</td>
                    <td>Monday</td>
                    <td>Tuesday</td>
                    <td>Wednesday</td>
                    <td>Thursday</td>
                    <td>Friday</td>
                    <td>Saturday</td>
                    <td>Sunday</td>
                    </thead>
                    <?php
                    $locations = Locations::model()->findAll();

                    $days_of_week = array(
                        'monday',
                        'tuesday',
                        'wednesday',
                        'thursday',
                        'friday',
                        'saturday',
                        'sunday'
                    );
                    foreach ($locations as $location) {
                        ?>
                        <tr class="location_row <?php echo $location['loc_name']; ?>">
                            <td class="location_name">
                                <?php
                                echo $location['loc_name'];
                                ?>
                            </td>
                            <?php
                                /**
                                 * this loop goes through the days of the week and 
                                 * provides the name of the days as well as a 
                                 * shortened (3 chars) version to use in the db call 
                                 */
                            ?>
                            <?php foreach ($days_of_week as $day): ?>
                                
                                <td class="date_hover" id="<?php echo $day; ?>">
                                    <?php
                                    /**
                                     * this code gets the times for each day and formats them the same as the timepicker
                                     * so that the timepicker can do comparisons.
                                     * 
                                     * this value is placed into a data-* attribute that can be used for the comparison in
                                     * assets/js/main.js
                                     */
                                    $short_day = substr($day, 0, 3);
                                    //turn time into date for formatting.. 
                                    //formatting is 'hh:mm:ss'
                                    $stringOpenTime = date('h:ia', strtotime($location['loc_' . $short_day . '_open_hrs']));
                                    $stringClosedTime = date('h:ia', strtotime($location['loc_' . $short_day . '_closed_hrs']));
                                    
                                    //trim leading zeros from formatted time
                                    $open_time = ltrim(
                                            $stringOpenTime, '0'
                                    );
                                    $close_time = ltrim(
                                            $stringClosedTime, '0'
                                    );
                                    ?>
                                    <div class="bootstrap-timepicker">
                                        <input title="Click to edit" data-time ="<?php echo $open_time; ?>" class="timepicker open" name="<?php echo $location['loc_name'] .'|'. $short_day . '_open_hrs'; ?>" value="<?php echo $open_time; ?>">
                                    </div>
                                    <span> to </span>

                                    <div class="bootstrap-timepicker">
                                        <input title="Click to edit" data-time ="<?php echo $close_time; ?>" class="timepicker closed" name="<?php echo $location['loc_name'].'|'. $short_day . '_closed_hrs'; ?>" value="<?php echo $close_time; ?>">
                                    </div>
                                </td>
                            <?php endforeach; ?>


                        </tr>
                        <?php
                    }//end foreach
                    ?>
                </table>
                <input id="time_submit" class="btn btn-primary" type="Submit" value="Submit Changes"/>
            </fieldset>
        </form>
    </div>

    <div id="holidayForm" class="admin-form">
        <h3 class="section-title">Holidays</h3>
        <form>
            <fieldset>
                <table id="timeTable" align="center" border="1">
                    <thead>
                    <td>Selected Shops</td>
                    <td>Holiday</td>
                    <td>Date</td>
                    <td>Description (optional)</td>
                    </thead>

                    <?php
                    $holidays = Holidays::model()->findAll();

                    foreach ($holidays as $holiday) {
                        //get all shop_holidays with the id of the current holiday
                        $shop_holidays = ShopHolidays::model()->findAllbyAttributes(
                                array(
                                    'hol_id' => $holiday['hol_id'],
                        ));
                        ?>
                        <tr>
                            <td>
                                <!--option for each location. "selected" if holiday applies to location-->
                                <select multiple class='selectpicker large-select'>
                                    <?php
                                    //get all locations
                                    foreach ($locations as $location) {
                                        //reset "selected" value
                                        $selected = '';

                                        //check loc in shop holiday list and compare to all locations
                                        //if they match, set "selected" to true
                                        foreach ($shop_holidays as $sh) {
                                            if ($sh['loc_id'] == $location['loc_id']) {
                                                $selected = 'selected';
                                            }
                                        }//end sh foreach
                                        ?>

                                        <option <?php echo $selected ?> value='<?php echo $location['loc_name'] ?>'><?php echo $location['loc_name'] ?></option>
                                        <?php
                                    }//end location foreach
                                    ?>
                                </select>
                            </td>
                            <td>
                                <?php
                                echo $holiday['hol_name'];
                                ?>
                            </td>
                            <td>
                                <?php
                                $hol_date = strtotime($holiday['hol_date']);
                                echo date('M d', $hol_date);
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $holiday['hol_description'];
                                ?>
                            </td>
                        </tr>
                        <?php
                    }//end shop holidays foreach
                    ?>

                </table>
                <input id="time_submit" class="btn btn-primary" type="Submit" value="Submit Changes"/>
            </fieldset>
        </form>
    </div>
</div>