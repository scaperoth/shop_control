
$(document).ready(function() {
    
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
        meridan:false
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
        }
        else{
            $(this).removeClass('changed');
        }
    });

    //better code is needed
    /**$('.timepicker').change(function() {
     $(this).addClass('changed');
     });*/
});



/**
 * 
 * @type @exp;document@call;getElementById
 */
var statBtn = document.getElementById('statusToggle');
var msg;

/**
 * 
 * @type @exp;document@call;getElementById
 */
if (statBtn) {
    /**
     * 
     * @type @exp;document@call;getElementById|@exp;document@call;getElementById
     */
    if (statBtn.value == 'open') {
        this.value = 'open';
        $(statBtn).removeClass('isclosed');
        $(statBtn).addClass('isopen');
        msg = 'The shop is now closed';

    }
    else {
        this.value = 'closed';
        $(statBtn).removeClass('isopen');
        $(statBtn).addClass('isclosed');
        msg = 'The shop is now open';
    }
    $('#toggleShopForm').submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        if (statBtn.value === 'open') {

            if (confirm('Are you sure you wish to close this shop?'))
            {
                /**
                 * toggle shop closed
                 */
                $.post('closeshop', $form.serialize(), function(json) {
                    console.log(json);
                });
                statBtn.value = 'closed';
                $(statBtn).removeClass('isopen');
                $(statBtn).addClass('isclosed');
                msg = 'The shop is now open';
            }
        }
        else {
            if (confirm('Are you sure you wish to open this shop?'))
            {
                /**
                 * toggle shop closed
                 */
                $.post('openshop', $form.serialize(), function(json) {
                    console.log(json);
                });
                statBtn.value = 'open';
                $(statBtn).removeClass('isclosed');
                $(statBtn).addClass('isopen');
                msg = 'The shop is now closed';
            }
        }
        return false;
    });
}

/**
 * clock
 */
var d = new Date;
$('#clock p').text('Cuurent time: ' + d.getHours() + ':' + d.getMinutes() + ":" + d.getSeconds());

setInterval(function() {
    d = new Date;
    $('#clock p').text('Cuurent time: ' + d.getHours() + ':' + d.getMinutes() + ":" + d.getSeconds());
}, 1000);

