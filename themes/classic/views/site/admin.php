<?php
/* @var $this HomeController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>

<h1>Admin Page</h1>

<div>
    <dfn class="description">Click times to edit.</dfn>
    <div id="classroomDataForm">
        <form id="classroomDateForm">
            <fieldset>
                <table id="timeTable" align="center" border="1">
                    <thead>
                    <td>Location</td>
                    <td>Open time</td>
                    <td>Close time</td>
                    </thead>
                    <?php
                    $locations = Locations::model()->findAll();


                    foreach ($locations as $location) {
                        ?>
                        <tr>
                            <td>
                                <?php
                                echo $location['loc_name'];
                                ?>
                            </td>
                            <td class="date_hover">
                                <input title="Click to edit" class="timepicker open" name="<?php echo $location['loc_name'] . '_open'; ?>" value="<?php echo $location['loc_open_hrs']; ?>">
                            </td>

                            <td class="date_hover">
                                <input title="Click to edit" class="timepicker close" name="<?php echo $location['loc_name'] . '_close'; ?>" value="<?php echo $location['loc_closed_hrs']; ?>">
                            </td>

                        </tr>
                        <?php
                    }//end foreach
                    ?>
                </table>
                <input id="time_submit" type="Submit" value="Submit Changes"/>
            </fieldset>
        </form>
    </div>

    <div id="holidayForm">
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
                                <select multiple>
                                <?php
                                //get all locations
                                foreach($locations as $location){
                                    //reset "selected" value
                                    $selected ='';
                                    
                                    //check loc in shop holiday list and compare to all locations
                                    //if they match, set "selected" to true
                                    foreach ($shop_holidays as $sh) {
                                        if($sh['loc_id'] == $location['loc_id']){
                                            $selected = 'selected';
                                        }
                                    }//end sh foreach
                                    ?>
                                    
                                    <option <?php echo $selected?> value='<?php echo $location['loc_name']?>'><?php echo $location['loc_name']?></option>
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
                <input id="time_submit" type="Submit" value="Submit Changes"/>
            </fieldset>
        </form>
    </div>
</div>