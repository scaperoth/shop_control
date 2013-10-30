// MSFunctionality
    var pDivID = 0
    function saveSelectionToSession(pvActionToPass){
        var cID = pvActionToPass.replace('CF','')
        var tempStr = ''
                                    
        $(".divlayer[id*='divlayer" + cID + "']").find(":checkbox:checked").each(function (index) {
            tempStr += $('label[for=' + this.id + ']').html() + "||";
        });
          
        if (tempStr!=''){
            $("#hlnkViewMulti" + cID).show();
        }else{
            $("#hlnkViewMulti" + cID).hide();
            document.getElementById("ddlCustomField" + cID).selectedIndex = 0
        }

        document.getElementById("hfSelectedItems" + cID).value = tempStr;
        $(".divlayer[id*='" + cID + "']").hide();
        hideModal(); 
    }
    
    function selectNone(pvCtrlID) {
        $(".divlayer[id*='divlayer" + pvCtrlID + "']").find(":checkbox").each(function() {
            if($(this).attr("disabled")){
                //skip
            }else{
                $(this).attr("checked", false);
            }
        });
    }
    
    function selectAll(pvCtrlID) {
        $(".divlayer[id*='divlayer" + pvCtrlID + "']").find(":checkbox").attr("checked", true);
    } 
    
    function hideModal() {
        $(".modallayer").remove();
    }
    
    function showViewSelectedLayer(pvLayerId) {
        if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '2' || document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '1')
            {
                if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '1'){
                    $(".divlayer[id*='divlayer" + pvLayerId + "']").find(":checkbox").attr("disabled", true);
                }else{
                    $(".divlayer[id*='divlayer" + pvLayerId + "']").find(":checkbox").attr("disabled", false);
                }
          
                /*Get the layer to show and the dimensions of it*/
                var lLayer = $(".divlayer[id*='" + pvLayerId + "']");
                pDivID = pvLayerId
                
                /*Calculate where to put the layer.*/
                /*The [top]  position must account for screen height, layer height and document scroll.*/
                /*The [left] position must account for screen width , layer width  and document scroll.*/
                var lTop = ($(window).height() / 2) - (lLayer.height() / 2) + $(document).scrollTop();
                var lLeft = ($(window).width() / 2) - (lLayer.width() / 2) + $(document).scrollLeft();
            
                /*Position and show the layer*/
                lLayer.css({ top: lTop + "px", left: lLeft + "px" });
                lLayer.before("<div class=\"modallayer\"></div>");
                var lHeight = $("body")[0].scrollHeight;
                var lWidth = $("body")[0].scrollWidth;
                $(".modallayer").css({ opacity: 0.7, height: lHeight + "px", width: lWidth + "px"});
               
                lLayer.show();
            }
        else
            {
                document.getElementById("hlnkViewMulti" + pvLayerId).style.display = 'none';
                selectNone(pvLayerId);
                 
                if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '1'){
                    ///SELECT ALL
                    selectAll(pvLayerId);
                    saveSelectionToSession("CF" + pvLayerId);
                }
            }
    } 
    
    function showLayer(pvLayerId) { 
        if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '2')
            {
                $(".divlayer[id*='divlayer" + pvLayerId + "']").find(":checkbox").attr("disabled", false);
                /*Get the layer to show and the dimensions of it*/
                var lLayer = $(".divlayer[id*='" + pvLayerId + "']");
                pDivID = pvLayerId
                /*Calculate where to put the layer.*/
                /*The [top]  position must account for screen height, layer height and document scroll.*/
                /*The [left] position must account for screen width , layer width  and document scroll.*/
                var lTop = ($(window).height() / 2) - (lLayer.height() / 2) + $(document).scrollTop();
                var lLeft = ($(window).width() / 2) - (lLayer.width() / 2) + $(document).scrollLeft();
            
                /*Position and show the layer*/
                lLayer.css({ top: lTop + "px", left: lLeft + "px" });
                lLayer.before("<div class=\"modallayer\"></div>");
                var lHeight = $("body")[0].scrollHeight;
                var lWidth = $("body")[0].scrollWidth;
                $(".modallayer").css({ opacity: 0.7, height: lHeight + "px", width: lWidth + "px"});
               
                lLayer.show();
           }
        else
           {
                document.getElementById("hlnkViewMulti" + pvLayerId).style.display = 'none';
                selectNone(pvLayerId);
                           
                if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '1'){
                    ///SELECT ALL
                    selectAll(pvLayerId);
                    saveSelectionToSession("CF" + pvLayerId);
                }

                if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex == '0') {
                    // alert("index = 0");
                    document.getElementById("hfSelectedItems" + pvLayerId).value = "";
                }


                if (document.getElementById("ddlCustomField" + pvLayerId).selectedIndex > '2'){
                    document.getElementById("hfSelectedItems" + pvLayerId).value = "";
                }
           }
    } 
    
    function closeLayer(pvLayerId) {
        $(".divlayer[id*='" + pvLayerId + "']").hide();
        hideModal(); 
                 
        //since the user is closing without saving we will need to refresh the chklist...
        //start by getting array of saved items...         
        var arrSplit = document.getElementById("hfSelectedItems" + pvLayerId.replace('divlayer','')).value.split("||");
       
        //now wipe all items in this check list...
        $(".divlayer[id*='divlayer" + pvLayerId.replace('divlayer','') + "']").find(":checkbox").attr("checked", false);       
   
        //for each checkbox item...       
        $(".divlayer[id*='divlayer" + pvLayerId.replace('divlayer','') + "']").find(":checkbox").each(function() {
            //now look through the array of saved items one-by-one for the checkbox value.. 
            var chkValue = $('label[for=' + this.id + ']').text(); 
            var chkItem = $(this)
            $(arrSplit).each(function(i,e) {
                //if the checkbox item we are currently on matches a value in the array of saved items then check true and exit
                if(chkValue == arrSplit[i]){
                    //found match, so check it and exit...
                    chkItem.attr("checked", true);  
                }     
            }); 
        });
               
        if (document.getElementById("hlnkViewMulti" + pvLayerId.replace('divlayer','')).style.display == 'none'){
            document.getElementById("ddlCustomField" + pvLayerId.replace('divlayer','')).selectedIndex = 0;
        }
    }
    
    function refreshLayer(pvLayerId) {
        $(".divlayer[id*='" + pvLayerId + "']").hide();
        hideModal();
    }
    
    $(document).ready(function() {
        $(".divlayer").hide();
    });
                
    $(window).resize(function() {
        if (pDivID > 0){
            refreshLayer(pDivID);
            showLayer(pDivID);
        }
    });