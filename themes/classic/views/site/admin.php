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


                    foreach ($locations as $location) {
                        ?>
                        <tr>
                            <td class="location_name">
                                <?php
                                echo $location['loc_name'];
                                ?>
                            </td>
                            <td class="date_hover" id="monday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_mon_open_hrs']; ?>">
                                </div>
                                <span> to </span>

                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_mon_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="tuesday">
                                <div class="bootstrap-timepicker">                                
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_tue_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                <div class="bootstrap-timepicker">       
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_tue_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="wednesday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_wed_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                 <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_wed_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="thursday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_thu_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_thu_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="friday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_fri_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_fri_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="saturday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_sat_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_sat_closed_hrs']; ?>">
                                </div>
                            </td>
                            <td class="date_hover" id="sunday">
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_sun_open_hrs']; ?>">
                                </div>
                                <span> to </span>
                                <div class="bootstrap-timepicker">
                                    <input title="Click to edit" class="timepicker closed" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_sun_closed_hrs']; ?>">
                                </div>
                            </td>

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