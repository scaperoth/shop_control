<?php
/* @var $this HomeController */
?>
<?php
$this->pageTitle = Yii::app()->name;
?>
test
<style>
    .alert-error{
        color: #165235;
        text-shadow: 0px 2px 3px #82c462;
        background-color:#8CCD8C;
    }
</style>

<div>
    <div id='flash'>
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert">&#215;</a>
                <div id="flash_error"><?php echo Yii::app()->user->getFlash('success'); ?></div>
            </div>
        <?php endif; ?>
    </div>
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
                        <tr class="location_row <?php echo $location['loc_name']; ?>" data-rel = '<?php echo md5($location['loc_id']); ?>'>
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
                                        <input title="Click to edit" data-time ="<?php echo $open_time; ?>" class="timepicker open" name="<?php echo $location['loc_name'] . '|' . $short_day . '_open_hrs'; ?>" value="<?php echo $open_time; ?>">
                                    </div>
                                    <span> to </span>

                                    <div class="bootstrap-timepicker">
                                        <input title="Click to edit" data-time ="<?php echo $close_time; ?>" class="timepicker closed" name="<?php echo $location['loc_name'] . '|' . $short_day . '_closed_hrs'; ?>" value="<?php echo $close_time; ?>">
                                    </div>
                                </td>
                            <?php endforeach; ?>


                        </tr>
                        <?php
                    }//end foreach
                    ?>
                </table>
                <input id="time_submit" class="btn btn-primary" type="Submit" value="Submit Changes"/>
                <a data-toggle="modal" id="add_location_btn" class="btn btn-info" href="#AddLocationDialog">Create a New Location</a>
                <a data-toggle="modal" id="add_location_btn" class="btn" href="#UpdateLocationDialog">Edit a Location</a>
                <a data-toggle="modal" id="delete_location_btn" class="btn btn-danger pull-right" href="#DeleteLocationDialog">Delete a Location</a>
            </fieldset>
        </form>

        <!---------add location dialog start----------->
        <div id="AddLocationDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="form">
                <form class="form-horizontal" id="addLocationForm" method="post" >       
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3>Add a new shop location.</h3>
                    </div>
                    <div class="modal-body">
                        <div class="divDialogElements">

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="locationname">Location Name:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-globe"></i></span>
                                            <input placeholder="Example: Funger" required class="span3" name="AddLocation[locationname]" id="locationname" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="ipaddress">IP Address:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-barcode"></i></span>
                                            <input placeholder="Example: 127.0.0.1" required class="span3" name="AddLocation[ipaddress]" id="ipaddress" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="computername">Computer Name:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-tag"></i></span>
                                            <input placeholder="Example: est00211w01" required class="span3" name="AddLocation[computername]" id="computername" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn small" onclick="closeDialog();" value="Cancel">
                        <input class="btn btn-primary" type="Submit" value="OK">
                    </div>
                </form>
            </div>
        </div><!--end modal window-->
        <!---------add location dialog end----------->

        <!---------update location dialog start----------->
        <div id="UpdateLocationDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="form">
                <form class="form-horizontal" id="updateLocationForm" method="post">       
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3>Edit a shop location.</h3>
                    </div>
                    <div class="modal-body">
                        <div class="divDialogElements">

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="locationselectupdate">Location Name:</label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-globe"></i></span>
                                            <select id='locationselectupdate' name='UpdateLocation[locationnameupdate]'>
                                                <?php foreach ($locations as $location): ?>
                                                    <option value='<?php echo md5($location['loc_id']); ?>'><?php echo $location['loc_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row--> 

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="ipaddressupdate">IP Address:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-barcode"></i></span>
                                            <input placeholder="Example: 127.0.0.1" required class="span3" name="UpdateLocation[ipaddressupdate]" id="ipaddressupdate" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="computernameupdate">Computer Name:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-tag"></i></span>
                                            <input placeholder="Example: est00211w01" required class="span3" name="UpdateLocation[computernameupdate]" id="computernameupdate" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn small" onclick="closeDialog();" value="Cancel">
                        <input class="btn btn-primary" type="Submit" value="OK">
                    </div>
                </form>
            </div>
        </div><!--end modal window-->
        <!---------update location dialog end----------->

        <!---------delete location dialog start----------->
        <div id="DeleteLocationDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="form">
                <form class="form-horizontal" id="deleteLocationForm" method="post">       
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3>Delete a location.</h3>
                    </div>
                    <div class="modal-body">
                        <div class="divDialogElements">

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="locationselect">Location Name:</label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-globe"></i></span>
                                            <select id='locationselect' name='DeleteLocation[locationname]'>
                                                <?php foreach ($locations as $location): ?>
                                                    <option value='<?php echo md5($location['loc_id']); ?>'><?php echo $location['loc_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn small" onclick="closeDialog();" value="Cancel">
                        <input class="btn btn-primary" type="Submit" value="OK">
                    </div>
                </form>
            </div>
        </div><!--end modal window-->
        <!---------delete location dialog end----------->

    </div><!--end classroom data form-->

    <div id='holiday_flash'></div>
    <div id="holidayForm" class="admin-form">
        <h3 class="section-title">Holidays</h3>
        <form id='shopHolidayForm'>
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
                                <select multiple name='holidayshops' class='selectpicker large-select'>
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

                                        <option <?php echo $selected ?>  value='<?php echo md5($location['loc_id']); ?>'><?php echo $location['loc_name'] ?></option>
                                        <?php
                                    }//end location foreach
                                    ?>
                                </select>
                            </td>
                            <td class='holiday' data-hol='<?php echo md5($holiday['hol_id']); ?>'>
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
                <a data-toggle="modal" id="add_holiday_btn" class="btn btn-info" href="#AddHolidayDialog">Create a New Holiday</a>
                <a data-toggle="modal" id="delete_holiday_btn" class="btn btn-danger pull-right" href="#DeleteHolidayDialog">Delete a Holiday</a>
            </fieldset>
        </form>

        <!---------add holiday dialog start----------->
        <div id="AddHolidayDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="HolidayLabel" aria-hidden="true">
            <div class="form">
                <form class="form-horizontal" id="addHolidayForm" method="post" action="../phpactions/addholiday">       
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3>Add a new holiday.</h3>
                    </div>
                    <div class="modal-body">
                        <div class="divDialogElements">

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="holidayname">Holiday Name:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-briefcase"></i></span>
                                            <input placeholder="Example: New Year's Day" required class="span3" name="AddHoliday[holidayname]" id="holidayname" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="hoildaydate">Holiday Date:<span class="required">*</span></label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-th"></i></span>
                                            <input class='datepicker' required placeholder="Example: Jan 01" class="span3" name="AddHoliday[holidaydate]" id="hoildaydate" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="holidaydescription">Holiday Description:</label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-comment"></i></span>
                                            <input placeholder="Example: Federal holiday" class="span3" name="AddHoliday[holidaydescription]" id="holidaydescription" type="text">
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn small" onclick="closeDialog();" value="Cancel">
                        <input class="btn btn-primary" type="Submit" value="OK">
                    </div>
                </form>
            </div>
        </div><!--end modal window-->
        <!---------add holiday dialog end----------->

        <!---------delete location dialog start----------->
        <div id="DeleteHolidayDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="DeleteHoliday" aria-hidden="true">
            <div class="form">
                <form class="form-horizontal" id="deleteHolidayForm" method="post">       
                    <div class="modal-header">
                        <a href="#" class="close" data-dismiss="modal">&times;</a>
                        <h3>Delete a holiday.</h3>
                    </div>
                    <div class="modal-body">
                        <div class="divDialogElements">

                            <!--new row-->
                            <div class="row">
                                <div class="control-group">
                                    <label class="control-label" for="holidayselect">Holiday Name:</label>
                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on"><i class="icon-briefcase"></i></span>
                                            <select id='holidayselect' name='DeleteHoliday[holidayselect]'>
                                                <?php foreach ($holidays as $holiday): ?>
                                                    <option value='<?php echo md5($holiday['hol_id']); ?>'><?php echo $holiday['hol_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div><!--end controls-->
                                </div><!--end control group-->
                            </div><!--end row-->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn small" onclick="closeDialog();" value="Cancel">
                        <input class="btn btn-primary" type="Submit" value="OK">
                    </div>
                </form>
            </div>
        </div><!--end modal window-->
        <!---------delete location dialog end----------->
    </div>


    <script>

                            function closeDialog() {
                                $('.modal').modal('hide');
                            }


    </script>