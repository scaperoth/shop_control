function onblurTEXTAREA(max,id,text_value){
	if(trimStr(text_value).length >= max){
		/* The value in the text field is too long trim it to length */
		document.getElementById(id).value = text_value.substring(0,max);
	}
}

function maxlengthTEXTAREA(e, max, text_value) {

	/* This error traps the event passed to this function */
	if(typeof(e) == "undefined"){
		/* The there is no event */
		return(false);
	}
	
	/* This is the regular expression that only alows printable characters */
	reg = /^[A-Za-z0-9!@#$%^&*()~`_+-=<,>.?\"'|\\{}[\]\n\f\r\t\v]/;
	
	/* This holds if the character entered is a printing character or not */
	var printing_char;
	
	if(!(typeof(e.charCode) == 'undefined')){
		/* NS 6+ & Mozilla 0.9+ */
		if(e.charCode == 0){
			/* This is a non printing character allow it through */
			printing_char = false;
		}
		else{
			/* Test if this is an allowable characer */
			printing_char = reg.test(String.fromCharCode(e.charCode));
		}
	}
	else{
		if(!(typeof(e.keyCode) == 'undefined')){
			/* IE & Opera */
			/* Test if this is an allowable characer */
			printing_char = reg.test(String.fromCharCode(e.keyCode));
		}
	}

	if(printing_char && (trimStr(text_value).length >= max)){
		/* This next character would put this field over its limit */
		
		return(false);
	}
	else{
		/* This next character would not add a character to the text field */
		/* Or we are not at the limit for this text field yet */
		return(true);
	}
	
	
}

function NonPrintingKey(key_code){
	switch(key_code)
	{
		/* Back space */
		case 8:
		/* Shift */
		case 16:
		/* Ctrl */
		case 17:
		/* Alt */
		case 18:
		/* Caps */
		case 20:
		/* Left Arrow */
		case 37:
		/* Up Arrow */
		case 38:
		/* Right Arrow */
		case 39:
		/* Down Arrow */
		case 40:
		/* Delete */
		case 46:
		
		/* This was a non printing character */
		return(true);
	}
	/* This was a printing character */
	return(false);
}

function trimStr(str){
    if (str == null) { return ""; }
    
	/* Trim leading white spaces */
	while(str.substring(0,1) == " "){
		str = str.substring(1,str.length);
	}
	/* Trim trailing white spaces */
	while (str.substring(str.length-1,str.length) == " "){
		str = str.substring(0,str.length-1);
	}
	/* return the trimmed string. */
	return str;
}

function CheckHexidecimalColor(text){
	var re = /^[A-Fa-f0-9]{6}/; /* This is the regular expression to validate hex numbers. */
	var le = text.length;		/* This is the length of the hexidecimal string. */
	
	/* Check to make sure the text is not too long. */
	if(le>6){
		/* Invalid Hex Number */
		return(false);
	}
	/* Check to make sure the text entered is valid. */
	if(text.search(re)==-1){
		/* Invalid Hex Number */
		return(false);
	}
	
	/* Valid Hex Number */
	return(true);
}

function CheckEmail(text)
{
	/*This function is used for javascript validation on emails*/
    var re = /^\w+([-+.][\w-]+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; ///^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	if(re.test(text)){return(true);}
	else{return(false);}
}

function KeyPressHexNumbersOnly(e){

	/* This error traps the event passed to this function */
	if(typeof(e) == "undefined"){
		/* The there is no event */
		return(false);
	}
	
	if(!(typeof(e.charCode) == 'undefined')){
		/* NS 6+ & Mozilla 0.9+ */
		if(e.charCode == 0){
			/* This is a non printing character allow it through */
			return(true);
		}
		else{
			reg = /^[A-Fa-f0-9]/;
			return(reg.test(String.fromCharCode(e.charCode)));
		}
	}
	
	if(!(typeof(e.keyCode) == 'undefined')){
		/* DOM */
		reg = /^[A-Fa-f0-9]/;
		return(reg.test(String.fromCharCode(e.keyCode)));
	}
}

function KeyPressNumbersOnly(e){
	/* This error traps the event passed to this function */
 
    if(typeof(e) == "undefined")
    {
		/* The there is no event */
        e.returnValue = false;
	}
	
	if(!(typeof(e.charCode) == 'undefined')){
		/* NS 6+ & Mozilla 0.9+ */
		if(e.charCode == 0){
			/* This is a non printing character allow it through */
	        e.returnValue = true;
		}
		else{
			reg = /\d/;
	        e.returnValue = reg.test(String.fromCharCode(e.charCode));
		}
	}
	
	if(!(typeof(e.keyCode) == 'undefined')){
		/* DOM */
        // alert(e.keyCode);
		reg = /\d/;
        e.returnValue = reg.test(String.fromCharCode(e.keyCode));
	}
}  

function checkNumber(){
    var retValue = true;

    if(parseInt(document.getElementById("txtNumEventAttachmentstext").value) > 20){
        alert('Please enter # of Event Attachments field between 0 to 20');
        document.getElementById("txtNumEventAttachmentstext").value = document.getElementById("hdNumEventAttachmentstext").value;
        retValue = false;
    }
    
    return retValue;
}

// This fuction will set the precision of a value.  If the value is invalid, then it will blank the field.
// Param: pElement - element id to set
// Param: pPrecision - Precision to set the value to
function validatePrecision(pElement, pPrecision){
    var pElement;
    var pPrecision;
    el=document.getElementById(pElement).value;
    if (el != "") {
        el = el.replace("$","");
        el = el.replace(",","");
        if (isNaN(el)==false){
            if (parseFloat(el) < 0){
                document.getElementById(pElement).value = "";
            } else {
                document.getElementById(pElement).value = (parseFloat(el)).toFixed(pPrecision);
            }
        } else {
            document.getElementById(pElement).value = "";
        }
    }

}

// This fuction will set the precision of a value.  If the value is invalid, then it will blank the field.
// Param: pElement - element id to set
// Param: pPrecision - Precision to set the value to
function validatePrecisionAllowNegative(pElement, pPrecision){
    var pElement;
    var pPrecision;
    el=document.getElementById(pElement).value;
    if (el != "") {
        el = el.replace("$","");
        el = el.replace(",","");
        if (isNaN(el)==false){
            if (parseFloat(el) == 0){
                document.getElementById(pElement).value = "0.00";
            } else {
                document.getElementById(pElement).value = (parseFloat(el)).toFixed(pPrecision);
            }
        } else {
            document.getElementById(pElement).value = "";
        }
    }

}

// This fuction will set the precision of a value.  If the value is invalid, then it will blank the field.
// Param: pAmount - Amount to set
// Param: pPrecision - Precision to set the value to
function SetPrecision(pAmount, pPrecision){
    var pPrecision;
    var ReturnAmt = 0;
    if (pAmount != "") {
        if (isNaN(pAmount)==false){
            if (parseFloat(pAmount) > 0){
                ReturnAmt = pAmount;
            }
        }
    }
    return (parseFloat(ReturnAmt)).toFixed(pPrecision);
}

// This fuction will set the precision of a value.  If the value is invalid, then it will blank the field.
// Param: pAmount - Amount to set
// Param: pPrecision - Precision to set the value to
function SetPrecisionAllowNegative(pAmount, pPrecision){
    var pPrecision;
    var ReturnAmt = 0;
    if (pAmount != "") {
        if (isNaN(pAmount)==false){
            if (parseFloat(pAmount) != 0){
                ReturnAmt = pAmount;
            }
        }
    }
    return (parseFloat(ReturnAmt)).toFixed(pPrecision);
}

function validateMaxRefund(pRegId, pPrecision){
    var pRegId;
    var pPrecision;
    var lRefund = "txtCreditAmt" + pRegId;
    var lMaxField = "hiddenMaxRefund" + pRegId;
    var lErrorLabel = "lblCancelErrors" + pRegId;
    
    validateMaxRefundAmount(lRefund,lMaxField,lErrorLabel);
}

function validateMaxRefundAmount(pRefund, pMaxField, pErrorLabel){
    var pRefund;
    var pMaxField;
    var pErrorLabel;
    
    lRefundAmt=document.getElementById(pRefund).value;
    lMaxAmt=document.getElementById(pMaxField).value;
    if (parseFloat(lRefundAmt) > parseFloat(lMaxAmt)){
        document.getElementById(pErrorLabel).innerText  = "The refund amount entered cannot be greater than the total payment amount.";
        document.getElementById(pRefund).value = lMaxAmt;
    } else {
        document.getElementById(pErrorLabel).innerText  = "";
    }
    
}