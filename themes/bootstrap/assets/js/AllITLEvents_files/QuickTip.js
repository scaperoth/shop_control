var qtID=""; /*This is the last quick tip link that was moused over.*/
var qtOpenID=""; /*This is the currenly opened quick tip.*/
var qtIFrame=null; /*This object holds the current quick tip iframe on the page.*/
var overqtIFrame=null;/*This traps when the mouse is over the open event iFrame.*/
var currentOpenTimeout=null;/*This handles all threads that create iframes.*/
var currentCloseTimeout=null;/*This handles all threads that remove iframes.*/

/*Detect if the browser is IE or not.*/
/*If it is not IE, we assume that the browser is NS.*/
var IE = document.all?true:false;


 var randomnumber;
 
/*************************************************************************************/
/* TO ADD QUICK LINKS TO A PAGE                                                      */
/* The two events must have functions added to them as show below.  The QuickTip.js  */
/* file must be included on the page making the quick tip calls using a script tag.  */
/* This quick tips work in IE & Firefox, Netscape & Opera do not allow mousing over  */
/* the quick tip pop up (they are screwy).                                           */
/*                                                                                   */
/* onmouseout="startexitingQuickTip();"                                              */
/* onmouseover="initQuickTip('event details http link','information id',event)       */
/*************************************************************************************/
       
function initQuickTip(Url,Iid,e){
  randomnumber=new Date().getTime();
    var lFacilityId = "";
    if(arguments.length>3){
        /*
        There an extra paremeter passed to the function.
        In 380 certain areas of the code pass a 4th Facility id parameter
        */
        
        lFacilityId = arguments[3];
    }

    removeTimouts();
    /*Set that the user is no longer of a quick tip.*/
    overqtIFrame=null;
    /*Set the quick tip to load and delay the actual load.*/
    qtID=Iid;
    /*Get the position of the mouse on the screen.*/
    var xPos;
    var yPos;
    if(IE==true){
        /*grab the x-y pos if browser is IE*/
        xPos = e.clientX + getScrollX();
        yPos = e.clientY + getScrollY();        
    }
    else{
        /*grab the x-y pos if browser is NS*/
        xPos = e.pageX;
        yPos = e.pageY;
    }  
    /*Catch possible negative values*/
    if(xPos < 0){xPos = 0;}
    if(yPos < 0){yPos = 0;}  
    
    //alert("IID: '"+Iid+"'");
    //lert("loadQuickTip('"+Url+"','"+Iid+"','"+xPos+"','"+yPos+"', '" + lFacilityId + "'");
    
    /*Set the new openning timeout.*/
    if (lFacilityId != ""){
        //alert("loadQuickTip('"+Url+"','"+Iid+"','"+xPos+"','"+yPos+"', '" + lFacilityId + "'");
        currentOpenTimeout = setTimeout("loadQuickTip('"+Url+"','"+Iid+"','"+xPos+"','"+yPos+"', '" + lFacilityId + "')", 500);
    }
    else {
        currentOpenTimeout = setTimeout("loadQuickTip('"+Url+"','"+Iid+"','"+xPos+"','"+yPos+"')", 500);
    }
}

function loadQuickTip(Url,Iid,xPos,yPos){
    var lFacilityId = "";
    if(arguments.length>4) {
        /*
        There an extra paremeter passed to the function.
        In 380 certain areas of the code pass a 5th Facility id parameter
        */
        lFacilityId = arguments[4];
    }
    
    //alert("IID: '"+Iid+"'");
    
      /*Only a timeout that has never expired is allowed to enter this function.*/
    if(qtID == Iid && qtOpenID != Iid){       
        /*Remove the old quick tip no matter what.*/
        if(qtIFrame != null){ removeQuickTip();}
        //alert("REMOVE OLD QTIP w/ IID: '"+Iid+"'");
        if(arguments.length>4){
        /*Make the new quick tip iFrame*/
        makeQuickTip(Url,Iid,xPos,yPos,lFacilityId);
        }
        else{
         /*Make the new quick tip iFrame*/
         //alert("MAKE NEW QTIP w/ IID: '"+Iid+"'");
         makeQuickTip(Url,Iid,xPos,yPos);
        }
    }      
}

function startexitingQuickTip(){
    /*The user has exited a quick tip link or is not over a quick tip.*/
    overqtIFrame=null;
    currentCloseTimeout = setTimeout("removeQuickTip()", 500);
}

function removeQuickTip(){
    /*Only a timeout that has never expired is allowed to enter this function.*/
    /*An iFrame can only be removed if a mouse is not over it as well.*/
    if((qtIFrame!=null) && (overqtIFrame==null)){
        /*Remove the iFrame from the page.*/
        document.body.removeChild(qtIFrame);

        qtIFrame=null;
        
        //alert("An iFrame has been totally removed from the page.");
                
        /*An iFrame has been totally removed from the page.*/
        qtID="";
        qtOpenID="";
    }
}

function overIFrame(){
    /*Flag that the user is over an iFrame.*/
    overqtIFrame=true;
    removeTimouts();
}

function makeQuickTip(Url,Iid,xPos,yPos){
    var lFacilityId = "";
    if(arguments.length>4) {
        /*
        There an extra paremeter passed to the function.
        In 380 certain areas of the code pass a 5th Facility id parameter
        */
        lFacilityId = arguments[4];
    }
   
    /*This function dynamically adds an iFrame to the page.*/
    qtIFrame = document.createElement("IFRAME");
   
  
    qtIFrame.setAttribute("id", "adxqtiframe" + randomnumber);
    qtIFrame.setAttribute("name", "adxqtiframe" + randomnumber);
    
    if(arguments.length>4){
        //alert("SETTING ATTRIBUTES: URL="+Url+" IID="+Iid+" FACILITYID="+lFacilityId);
        qtIFrame.setAttribute("src", Url+"="+Iid+"&FacilityId="+lFacilityId);
    }
    else
    {
        //alert("SETTING ATTRIBUTES: URL="+Url+" IID="+Iid);
        qtIFrame.setAttribute("src", Url+"="+Iid);
    }

 
    qtIFrame.setAttribute("width", "380px");
    qtIFrame.setAttribute("height", "250px");
    qtIFrame.setAttribute("scrolling", "auto");
    qtIFrame.setAttribute("frameBorder", "no");
    qtIFrame.setAttribute("marginWidth", "0");
    qtIFrame.setAttribute("marginHeight", "0");
    qtIFrame.onmouseover = overIFrame;
    qtIFrame.onmouseout = startexitingQuickTip;
    /*Position the iframe correctly.*/
    qtIFrame.style.position = "absolute";
    qtIFrame.style.zIndex = 1000056;
    qtIFrame.className = "quicktipborder";
    
    /*Get the available on screen space*/
    var browserWidth=getBrowserWidth();
    /*This is the right of the quick tip.*/
    var xPosRight=parseInt(xPos)+parseInt(380);
    
    if(xPosRight > browserWidth){
        /*Move the quick tip on screen*/
        var newLeft = parseInt(xPos)-parseInt(380);
        qtIFrame.style.left = newLeft+"px";
    }
    else{
        /*Leave it where it is.*/
        qtIFrame.style.left = xPos+"px";
    }
    
    /*var scrollY=getScrollY();+parseInt(scrollY)*/
    qtIFrame.style.top = parseInt(yPos)+"px";/*The y position, this gets adjusted in setIFrameSize() if needed.*/
    
    document.body.appendChild(qtIFrame);
    
    /*Set the quick tip parameters*/
    qtID=Iid;
    qtOpenID=Iid;
        //alert("qt ID: '"+qtID+"'");
        //alert("qtOPEN ID: '"+qtOpenID+"'");
}

function setIFrameSize(){
    try
    {
        /*This is the actual quick tip area in the iframe.*/
        var qt;
        var qtFrame;
        
        if(IE==true){
            qt = frames["adxqtiframe" + randomnumber].document
            qtFrame = document.getElementById("adxqtiframe" + randomnumber);        
        }
        else{
            qtFrame = document.getElementById("adxqtiframe" + randomnumber)
            qt = qtFrame.contentDocument;
        }  
    
        var x = 380; /*default width*/
        if(qt != null){
            /*Get the size of the quick tip inside the iframe.*/
            qtbl = qt.getElementById("adx_qt_tbl");
            if(qtbl != null){x=qtbl.clientWidth+5;}
        }
              
        if(x>500){/*The iFrame is not allowed to have more than a 500px width.*/
            qtFrame.width = "500px";
            x=500;/*This is used for the next block of code*/
        }
        else{
            /*The iFrame size is less than 500px this is valid.*/
            qtFrame.width = x+"px";
        }
        
        var y = 445; /*default height*/
        if(qt != null){
            /*Get the size of the quick tip inside the iframe.*/
            qtbl = qt.getElementById("adx_qt_tbl");
            if(qtbl != null){y=qtbl.clientHeight+10;}
        }
        
        if(y>500){/*The iFrame is not allowed to have more than a 500px height.*/
            qtFrame.height = "500px";
            y=500;/*This is used for the next block of code*/
        }
        else{
            /*The iFrame size is less than 500px this is valid.*/
            qtFrame.height = y+"px";
        }
        
        var scrollY=getScrollY(); /*This holds how far this page has scrolled*/
        var bHeight=getBrowserHeight(); /*Ammount of space in browser window*/
        var qtFrameTop=parseInt(qtFrame.style.top.replace(/px/,""));
        var yPosTop=parseInt(qtFrameTop)+parseInt(scrollY); /*top of quick tip.*/
        var yPosBottom=parseInt(yPosTop)+parseInt(y); /*bottom of quick tip.*/
        var yOffScreen=parseInt(yPosBottom)-parseInt(bHeight)-parseInt(scrollY); /*ammount off screen*/

        /*alert("scrollY="+scrollY+"\nbHeight="+bHeight+"\nqtFrameTop="+qtFrameTop+"\nyPosTop="+yPosTop+"\nyPosBottom="+yPosBottom+"\nyOffScreen="+yOffScreen);*/
        /*if(yOffScreen > 0){*/
            /*Slide the quick tip up.  Whatever scrolled off the bottom move up.*/
            /*var newTop=parseInt(yPosTop) - parseInt(yOffScreen) - parseInt(25);*/
            /*qtFrame.style.top = newTop+"px";*/
        /*}*/
        /*else{Leave it where it is.}*/
    }
    catch(err)
    {
        /*Could Not Be Resized*/
    }
}

function removeTimouts(){
    /*Remove any closing timeouts*/
    if(currentCloseTimeout != null){
        clearTimeout(currentCloseTimeout);
        currentCloseTimeout=null;
    }
    /*Remove any openning timeouts.*/
    if(currentOpenTimeout != null){
        clearTimeout(currentOpenTimeout);
        currentOpenTimeout=null;
    }
}

function getBrowserWidth(){
    var myWidth=0;
    
    if(typeof(window.innerWidth) == 'number'){
        /*Non-IE*/myWidth = window.innerWidth;
    }
    else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
        /*IE 6+ in 'standards compliant mode'*/myWidth = document.documentElement.clientWidth;
    }
    else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
        /*IE 4 compatible*/myWidth = document.body.clientWidth;
    }
    return myWidth;
}

function getBrowserHeight(){
    var theHeight=0;
    
    if(typeof(window.innerWidth) == 'number'){
        //Non-IE
        theHeight = window.innerHeight;
    } else if(document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)){
        //IE 6+ in 'standards compliant mode'
        theHeight = document.documentElement.clientHeight;
    } else if(document.body && (document.body.clientWidth || document.body.clientHeight)){
        //IE 4 compatible
        theHeight = document.body.clientHeight;
    }
    
    return theHeight;
}

function getScrollY() {
  var scrOfY = 0;
  if(typeof(window.pageYOffset)=='number'){
    /*Netscape compliant*/scrOfY = window.pageYOffset;
  }
  else if(document.body && (document.body.scrollTop)){
    /*DOM compliant*/scrOfY = document.body.scrollTop;
  }
  else if(document.documentElement && (document.documentElement.scrollTop)){
    /*IE6 standards compliant mode*/scrOfY = document.documentElement.scrollTop;
  }
  return scrOfY;
}

function getScrollX() {
  var scrOfX = 0;
  if(typeof(window.pageXOffset)=='number'){
    /*Netscape compliant*/scrOfX = window.pageXOffset;
  }
  else if(document.body && (document.body.scrollLeft)){
    /*DOM compliant*/scrOfX = document.body.scrollLeft;
  }
  else if(document.documentElement && (document.documentElement.scrollLeft)){
    /*IE6 standards compliant mode*/scrOfX = document.documentElement.scrollLeft;
  }
  return scrOfX;
}