

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

/**
 * 
 * @type @exp;@call;$@call;val
 */
var start_time;
var change_time;
$('.timepicker').click(function() {

    start_time = $(this).attr('data-time');

});
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

/**
 * 
 */
//when time table is submitted post to updatehours
$('#classroomDateForm').submit(function(e) {
    e.preventDefault();

    //create array of which rows to update
    var objArray = $(".update");
    var idArray = [];
    $(objArray).each(function() {
        idArray.push($(this).data('rel'));
    });

    var data = $('#classroomDateForm').serializeArray();
    data.push({name: 'rows', value: idArray});
    /**
     * 
     */
    $.post('updatehours', data, function(data) {
        $('#flash').load('flashmsg');
        $('.location_row').removeClass('update');
        $('.timepicker').removeClass('changed');

    }
    );
});

/**
 * 
 */
$('#addLocationForm').submit(function(e) {
    e.preventDefault();
    $.post('addlocation', $(this).serialize(), function(data) {
        location.reload();
    });
});

/**
 * 
 */
$('#deleteLocationForm').submit(function(e) {
    e.preventDefault();
    $.post('deletelocation', $(this).serialize(), function(data) {
        location.reload();
    });
});

/**
 * 
 */
$('#shopHolidayForm').submit(function(e) {
    e.preventDefault();
    //create array of which rows to update
    var objArray = $(".holiday");
    var holiday_ids = [];
    $(objArray).each(function() {
        holiday_ids.push($(this).data('hol'));
    });
    var location_ids = serealizeSelects($('.selectpicker'));
    $.post('holidayupdate', {locations: location_ids, holidays: holiday_ids}, function(data) {
        console.log(data);
        $('#holiday_flash').load('flashmsg');
    }
    );
});

/**
 * 
 */
$('#addHolidayForm').submit(function(e) {
    e.preventDefault();
    $.post('addholiday', $(this).serialize(), function(data) {
        console.log(data);
    });
});

/**
 * 
 */
$('#deleteHolidayForm').submit(function(e) {
    e.preventDefault();
    $.post('deleteholiday', $(this).serialize(), function(data) {
        console.log(data);
    });
});

/**
 * Convert select to array with values
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