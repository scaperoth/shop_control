<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
$open_or_closed = (($this->current_state) ? 'open' : 'closed');
?>

<?php if ($this->current_state != ''): ?>
    <div>
        <h3 id="statusUpdate">This Support Center is now:</h3>
    </div>

    <div class="center" id="buttonControl">
        <form class="form-horizontal" data-status ='<?php echo $open_or_closed;?>' id='toggleShopForm' action='<?php echo (($open_or_closed=='open')?'../api/changeshopstatus/mylocation/close':'../api/changeshopstatus/mylocation/open');?>' method='POST'>
            <fieldset>
                <input type="submit" id="statusToggle" class='<?php echo 'is' . $open_or_closed; ?>' value="<?php echo $open_or_closed; ?>"/>
            </fieldset>
        </form>
        

        <div>
            <span class="description">{ click to change state }</span>
        </div>

        <div id="clock">
            <p>

            </p>
        </div>
    </div>
<?php endif; ?>
<?php if ($this->current_state == ''): ?>
    <div>
        <h3 id="statusUpdate" style="margin:30px;">You are not at a shop location.</h3>
    </div>
<?php endif; ?>