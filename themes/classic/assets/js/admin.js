
/********************************************
 * admin page control
 * controls all form submissions and 
 * style settings for elements on 
 * this page that use bootstrap plugin styles
 **********************************************/

/* 
 * admin format and time change
 */
$("table").tablecloth({
    theme: "default",
    striped: true,
    condensed: true,
    clean: true,
    cleanElements: "th td"
});

$('.selectpicker').selectpicker({
    width: '100%'
});
/**
 * 
 * @type @exp;document@call;getElementById|@exp;document@call;getElementById
 */
$('.timepicker').timepicker({
    className: 'dropdown',
    appendTo: $('.limiter'),
    meridan: false
});

/***********************************************************
 * AJAX calls to php actions for all buttons on admin page.
 */


var start_time;
var change_time;

/*set click of time picker to change "start_time" static variable */
$('.timepicker').click(function() {

    start_time = $(this).attr('data-time');

});

/*on change, if the new value is different than the start value, add changed
 * class and add "update" class to the parent row of the item
 * 
 * otherwise, if the time is changed back to what is already reflected
 * in the database, remove the "update" class
 * 
 * the "update" class tells the php action, updateHours, which rows
 * to update in the DB
 * 
 * TODO find better solution instead of adding update class. may be security issue
 */
$('.timepicker').change(function() {
    change_time = $(this).val();
    if (change_time != start_time) {
        $(this).addClass('changed');
        $(this).parent('.bootstrap-timepicker').parent('.date_hover').parent('.location_row').addClass('update');
    }
    else {
        $(this).removeClass('changed');
        $(this).parent('.bootstrap-timepicker').parent('.date_hover').parent('.location_row').removeClass('update');
    }
});

/*when time table is submitted post to updatehours*/
$('#classroomDateForm').submit(function(e) {
    e.preventDefault();

    //create array of which rows to update
    var objArray = $(".update");
    var idArray = [];
    //push the attribute, data-rel, of each "update" row into the array
    $(objArray).each(function() {
        idArray.push($(this).data('rel'));
    });

    var data = $('#classroomDateForm').serializeArray();
    /*create php-friendly array variable*/
    data.push({name: 'rows', value: idArray});

    $.post('../phpactions/updatehours', data, function(data) {
        $('#flash').load('flashmsg');
        $('.location_row').removeClass('update');
        $('.timepicker').removeClass('changed');

    }
    );
});

//TODO remove function and put inline as post action on the form tag
/*post location to add*/
$('#addLocationForm').submit(function(e) {
    e.preventDefault();
    $.post('../phpactions/addlocation', $(this).serialize(), function(data) {

        location.reload();
    });
});

//initally populate location update form with location data
populate_loc_data();
$("#locationselectupdate").change(function() {
    populate_loc_data();
});

function populate_loc_data() {
    $.post('../phpactions/getlocationinfo', $('#locationselectupdate').serialize(), function(data) {
        var loc_attr = JSON.parse(data);
        $('#ipaddressupdate').val(loc_attr.ip);
        $('#computernameupdate').val(loc_attr.name);
    });
}

/*post which location to update */
$('#updateLocationForm').submit(function(e) {
    e.preventDefault();
    $.post('../phpactions/updatelocation', $(this).serialize(), function(data) {
        console.log(data);
        location.reload();
    });
});
////////////////////////////////////////////////////////////////////////////////////////////


/*post which location to delete */
$('#deleteLocationForm').submit(function(e) {
    e.preventDefault();
    $.post('../phpactions/deletelocation', $(this).serialize(), function(data) {
        location.reload();
    });
});

/*post all holidays to update on change*/
$('#shopHolidayForm').submit(function(e) {
    e.preventDefault();

    //create array of which rows to update
    var objArray = $(".holiday");
    var holiday_ids = [];
    $(objArray).each(function() {
        holiday_ids.push($(this).data('hol'));
    });
    var location_ids = serealizeSelects($('.selectpicker'));
    $.post('../phpactions/holidayupdate', {locations: location_ids, holidays: holiday_ids}, function(data) {
        console.log(data);
        $('#holiday_flash').load('flashmsg');
    }
    );
});

$('#addHolidayForm').submit(function(e) {
    e.preventDefault();
    $.post('../phpactions/addholiday', $(this).serialize(), function(data) {
        console.log(data);
        location.reload();
    });
});

/*post holiday to delete*/
$('#deleteHolidayForm').submit(function(e) {
    e.preventDefault();
    $.post('../phpactions/deleteholiday', $(this).serialize(), function(data) {
        console.log(data);
        location.reload();
    });
});

/**
 * Convert select to array with values
 * Used in: shopHolidayForm.submit(function(){...});
 * @param {type} select
 * @returns {Array}
 */
function serealizeSelects(select)
{
    var array = [];
    select.each(function() {
        array.push(
                $(this).val()
                );
    });
    return array;
}