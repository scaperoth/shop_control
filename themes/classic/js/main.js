var statBtn = document.getElementById('statusToggle');
var msg;
if(statBtn){
    statBtn.onclick = function(){
        if (this.value=='open'){
            if(confirm('Are you sure you wish to close this shop?'))
            {
                this.value= 'closed';
                msg = 'The shop is now open';
            }
        }
        else{
            if(confirm('Are you sure you wish to open this shop?'))
            {    
                this.value='open';
                msg = 'The shop is now closed';
            }   
        }    
    }
}
