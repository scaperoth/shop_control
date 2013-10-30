function displayMultipleCategoryWindow(ddl)
{           
    if (arguments.length == 1 && arguments[0].selectedIndex == 2) {
        var new_window = window.open('CategorySubCategorySelect.aspx', '', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=380,height=400,left=212,top=50');
		new_window.focus();
		ddl.selectedIndex = 0;
    }                        
    else if(document.Form1.dlstCategory.selectedIndex == 2){
		var new_window = window.open('CategorySubCategorySelect.aspx', '', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=380,height=400,left=212,top=50');
		new_window.focus();
		document.Form1.dlstCategory.selectedIndex = 0;
    }            
}

function displayMultipleLocationWindow(){                                    
    if(document.Form1.dlstLocation.selectedIndex == 2){
        /*If there is a value on the document that holds a location format...get it*/
        /*This field should be on the CalendarNOW.aspx & EventList.aspx pages.*/
        var hiddenLocationFormatField=document.getElementById("p_LocationColumnField");
        var locationFormat="";
        if(hiddenLocationFormatField!=null){locationFormat=hiddenLocationFormatField.value;}
        
		var new_window = window.open('LocationBuildingSelect.aspx?lFormat='+locationFormat, '', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=380,height=400,left=212,top=50');
		new_window.focus();
		document.Form1.dlstLocation.selectedIndex = 0;
    }       
}

function eventdetailsview(Eid,Iid){
	//Show the onscreen form element for this item
	document.getElementById("ifrmDetails").src = "eventdetailsview.aspx?Eid=" + Eid + "&Iid=" + Iid;
	document.getElementById("ifrmDetails").style.visibility="visible";
	document.getElementById("divDetails").style.visibility="visible";
	
	var contentwidth = document.getElementById("CalendarPanel").clientWidth;
	var contentoffsettop = document.getElementById("divContent").offsetTop;
	var scrolloffset = document.body.scrollTop;
	var detailswidth = document.getElementById("divDetails").clientWidth;
	
	if((parseInt(contentwidth) - parseInt(detailswidth)) > 0){
		//Offset the popup the different of the two values from the left
		document.getElementById("divDetails").style.left = (parseInt(contentwidth) - parseInt(detailswidth)) / 2;
	}
	else{
		//Do not offset the popup from the left
		document.getElementById("divDetails").style.left = 0;
	}
	
	if((parseInt(scrolloffset) - parseInt(contentoffsettop)) > 0){
		//Offset the popup the different of the two values from the top
		document.getElementById("divDetails").style.top = parseInt(scrolloffset) - parseInt(contentoffsettop);
	}
	else{
		//Do not offset the popup
		document.getElementById("divDetails").style.top = 0;
	}
}

function closeIFrame(){
	document.getElementById("ifrmDetails").style.visibility="hidden";
	document.getElementById("divDetails").style.visibility="hidden";
}

function eventdetailsview_popup(Eid,Iid,Print){
	if(Print == "Y"){
		var new_window = window.open("EventDetailsView.aspx?Eid=" + Eid + "&Iid=" + Iid + "&print=print", "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0,width=650,height=480,left=50,top=50");
		new_window.focus();
	}
	else{
		var new_window = window.open("EventDetailsView.aspx?Eid=" + Eid + "&Iid=" + Iid, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=0,width=650,height=480,left=50,top=50");
		new_window.focus();
	}
}

function openInternetBrowser(link){
	var new_window = window.open(link, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600");
	new_window.focus();
}

function openInternetBrowserResizable(link){
	var new_window = window.open(link, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=600");
	new_window.focus();
}

function displaySelectedCategoriesWindow(){                                    
	var new_window = window.open('SelectedCategoryList.aspx', 'Category\SubCategory', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=380,height=228,left=212,top=50');
	new_window.focus();
	document.Form1.dlstCategory.selectedIndex = 0;
}

function displayEmailToFriend(Eid,Iid,InAdmin)
{
	var new_window = window.open('emailtofriend.aspx?Eid=' + Eid + '&Iid=' + Iid + '&Frm=' + InAdmin, "Email", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0');
	new_window.focus();
}

function iCalendarHowTo(Eid,Iid,InAdmin)
{
	var new_window = window.open('outlookhowto.aspx?Eid=' + Eid + '&Iid=' + Iid + '&Frm=' + InAdmin, "ICalendar", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0');
	new_window.focus();
}

function notifyMe(Eid,Iid,Frm)
{
	var new_window = window.open('notifyme.aspx?Eid=' + Eid + '&Iid=' + Iid + '&Frm=' + Frm, "Notify", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0');
	new_window.focus();
}

function RemindMe(Eid,Iid,Frm)
{
	var new_window = window.open('RemindMe.aspx?Eid=' + Eid + '&Iid=' + Iid + '&Frm=' + Frm, "Notify", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0');
	new_window.focus();
}

function registerMe(Rid,Iid,Frm)
{
	var new_window = window.open('EventRegistration.aspx?Rid=' + Rid + '&Iid=' + Iid + '&Frm=' + Frm, "Register", "");
	new_window.focus();
}

function rssType() {
    openInternetBrowser("rsstype.aspx")
    //var new_window = window.open("rsstype.aspx", "RssType", "");
    //new_window.focus();
}

function displayViewInfo(iType)
{
	var new_window = window.open('ViewInfo.aspx?InfoType=' + iType, "", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1');
	new_window.focus();
}

function downloadType(url,e)
{
	var new_window = window.open(url , "Download", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,left=' + (e.screenX - 375) + ',top=' + (e.screenY - 70));
	new_window.focus();
}

function eventChangeLog(Eid,Task)
{
	var new_window = window.open("EventChangeLogView.aspx?Eid="+Eid+"&task="+Task, "", 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300,left = 25,top = 25');
	new_window.focus();
}

function commentLog(Eid)
{
	var new_window = window.open("EventCommentLogView.aspx?Eid="+Eid, "Comments", 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300,left = 25,top = 25');
	new_window.focus();
}

function calendarSubscription(){
    var new_window = window.open("subscribetocalendar.aspx", "Subscribe", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300,left=25,top=25");
	new_window.focus();
}

function facilitySchedule(act,loc,bld,rm){
    var new_window = window.open("calendarfacilityutility.aspx?action="+act+"&l="+loc+"&b="+bld+"&r="+rm, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=475,height=350,left=25,top=25");
	new_window.focus();
}

function facilityScheduleFromSession(act,type){
    var new_window = window.open("calendarfacilityutility.aspx?action="+act+"&type="+type, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=475,height=350,left=25,top=25");
	new_window.focus();
}

function matchWndw(match_unmatched,rid){
    var new_window = window.open("eventscheduleutility.aspx?action="+match_unmatched+"&rid="+rid, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=360,height=250,left=25,top=25");
	new_window.focus();
}

function openTimesWindow(from_page){
    var new_window = window.open("eventscheduleutility.aspx?action=times&from="+from_page, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=550,height=285,left=25,top=25");
	new_window.focus();
}

function FacDtlsPopup(link){
	var new_window = window.open(link, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=700,height=600");
	new_window.focus();
}

function openFacilitySetupPopup(action,rid,bid,lid){
	var new_window = window.open("facilitydatapopup.aspx?action="+action+"&rid="+rid+"&bid="+bid+"&lid="+lid, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=300");
	new_window.focus();
}

function openFacilityResourcePopup(action,cid){
	var new_window = window.open("facilitydatapopup.aspx?action="+action+"&cid="+cid, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=300");
	new_window.focus();
}

function openScheduleRestrictionsPopup(action,ids){
	var new_window = window.open("facilitydatapopup.aspx?action="+action+"&uid="+ids, "", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=300");
	new_window.focus();
}