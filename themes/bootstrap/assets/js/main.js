
$(document).ready(function() {

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
        }
        else {
            $(this).removeClass('changed');
        }
    });

    //better code is needed
    /**$('.timepicker').change(function() {
     $(this).addClass('changed');
     });*/
});





$('#toggleShopForm').submit(function(e) {
    e.preventDefault();
    var status = ($('#toggleShopForm').attr('data-status')=='open')?'close':'open';
    if (confirm('You are about to '+status+' this shop. Proceed?')) {
        $.post($('#toggleShopForm').attr('action'), $(this).serialize(), function(json) {
            location.reload();
        });
    }
});

/**
 * clock
 */
var d = new Date;
$('#clock p').text('Cuurent time: ' + d.getHours() + ':' + d.getMinutes() + ":" + d.getSeconds());

setInterval(function() {
    d = new Date;
    $('#clock p').text('Cuurent time: ' + d.getHours() + ':' + d.getMinutes() + ":" + d.getSeconds());
}, 1000);

