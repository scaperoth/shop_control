/**
 * 
 * @type @exp;document@call;getElementById|@exp;document@call;getElementById
 */
$('.timepicker').timepicker();
$('.timepicker').change(function() {
    $(this).css('background', '#CCFFFF');
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
    if (statBtn.value === 'open') {
        this.value = 'open';
        statBtn.className = 'isopen';
        msg = 'The shop is now closed';

    }
    else {
        this.value = 'closed';
        statBtn.className = 'isclosed';
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
                $.post('openshop', $form.serialize(), function(json) {
                            console.log(json);                    
                });
                statBtn.value = 'closed';
                statBtn.className = 'isclosed';
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
                statBtn.className = 'isopen';
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

