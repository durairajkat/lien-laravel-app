// common_form_fields.js
//
// Written by Richard Sharp
// for Web Services Corp
// 2006-10-30
//
// Copyright (C) 2006-2007 Web Services Corp
// This file and the code contained in it are property of Web Services Corp, inc, and may not be used or distributed without express permission.

var aPhones=new Array();
var aNumbers=new Array();
var aDates=new Array();
var oCurrentField;
var oNextFocus;
var sLastBGColor="#FFFFFF";

//this function will set the focus to the first visible field
function focusFirst() {
	var oForm=document.forms[0];
	var oField;
	var bFound=false;
	if(!oForm) return null;
	var i=0;
	while((i<oForm.elements.length) && !bFound) {
		oField=oForm.elements[i];
		if((oField.type=="text") || (oField.type=="select-one") || (oField.type=="textarea")) {
			try{
				oField.focus();
				bFound=true;
			}
			catch(e) {
				//
			}
		}
		i++;
	}
}

// This function registers and stores date form fields and adds functionality
// to them so that when they lose focus, it attempts to parse the date and 
// re-format it
function registerDateField(oField,sDelimiter) {
	if(!sDelimiter) sDelimiter="/";
	var oDate=new Array(oField,sDelimiter);
	aDates[aDates.length]=oDate;
	oField.onkeydown=function(e) {
		oField=this;
		if(!e) e=window.event;
		var aDate=findDateField(oField);
		if(!aDate) return(false);
		if(
		   	//removed enter 13
		   	((e.keyCode<48) || (e.keyCode>57)) && 
			(
			 	(e.keyCode!=189) && 
				(e.keyCode!=190) && 
				(e.keyCode!=191) && 
				(e.keyCode!=9) &&
				(e.keyCode!=16) &&
				(e.keyCode!=8)  &&
				(e.keyCode!=37)  &&
				(e.keyCode!=38)  &&
				(e.keyCode!=39)  &&
				(e.keyCode!=40)  &&
				(e.keyCode!=13)  &&
				(e.keyCode!=27)  &&
				(e.keyCode!=46) 
				&& (e.keyCode!=96)
				&& (e.keyCode!=97)
				&& (e.keyCode!=98)
				&& (e.keyCode!=99)
				&& (e.keyCode!=100)
				&& (e.keyCode!=101)
				&& (e.keyCode!=102)
				&& (e.keyCode!=103)
				&& (e.keyCode!=104)
				&& (e.keyCode!=105)
				&& (e.keyCode!=109)
				&& (e.keyCode!=110)
				&& (e.keyCode!=111)
			) 
			) {
			e.returnValue=false;
			return(false);
		}
	}
	oField.onfocus=function(e) {
		try {
			oField.select();
		}
		catch(e) {
			
		}
	}
	var oldonblur = oField.onblur;
	oField.onblur=function(e) {
		//oField=this;
		if(oField.value=="") {
			if (typeof oldonblur == 'function') {
				return oldonblur();
			}
			return(true);
		}
		if(!e) e=window.event;
		var aDate=findDateField(oField);
		var sDelimiter=aDate[1];
		var thisDelimiter="";
		if(!aDate) return(false);
		var Month=0;
		var Day=0;
		var Year=0;
		var Today=new Date();
		var TestMonth=0;
		var TestYear=0;
		var TestYear=0;
		if(oField.value.indexOf("/")>0) thisDelimiter="/";
		if(oField.value.indexOf(".")>0) thisDelimiter=".";
		if(oField.value.indexOf("-")>0) thisDelimiter="-";
		if(thisDelimiter) {
			aParts=oField.value.split(thisDelimiter);
			//see if a 2-part or 3-part date has been entered and is valid
			if(aParts.length==2) {
				if((aParts[0]>0) && (aParts[0]<13) && (aParts[1]>0) && (aParts[1]<32)) {
					Month=aParts[0];
					Day=aParts[1];
					Year=Today.getFullYear();
				}
			}
			if(aParts.length==3) {
				if((aParts[0]>0) && (aParts[0]<13) && (aParts[1]>0) && (aParts[1]<32)) {
					Month=aParts[0];
					Day=aParts[1];
					if(parseInt(aParts[2],10)<100) {
						if(aParts[2]<70) Year=2000 + parseInt(aParts[2],10);
						else Year=1900 + parseInt(aParts[2],10);
					}
					else {
						if(parseInt(aParts[2],10)<1900) Year=Today.getFullYear();
						else Year=aParts[2];
					}
				}
			}
		}
		if(!Month) {
			//try to break it down just from numbers. Possabilities:
			// 3 digits - 1 Digit month, 2 Digit Day
			// 4 digits - 2 Digit Month, 2 Digit Day
			// 5 digits - 1 Digit Month 2 Digit Day, 2 Digit Year
			// 6 digits - 2 digit month, 2 digit day, 2 digit year
			// 8 digits - 2 digit month, 3 digit day, 4 digit year
			if(oField.value.length==3) {
				TestMonth=oField.value.substring(0,1);
				TestDay=oField.value.substring(1,3);
				TestYear=0;
			}
			if(oField.value.length==4) {
				TestMonth=oField.value.substring(0,2);
				TestDay=oField.value.substring(2,4);
				TestYear=0;
				if(TestMonth>12) {
					TestMonth=oField.value.substring(0,1);
					TestDay=oField.value.substring(1,2);
					TestYear=oField.value.substring(2,4);
				}
			}
			if(oField.value.length==5) {
				TestMonth=oField.value.substring(0,1);
				TestDay=oField.value.substring(1,3);
				TestYear=oField.value.substring(3,5);
			}
			if(oField.value.length==6) {
				TestMonth=oField.value.substring(0,2);
				TestDay=oField.value.substring(2,4);
				TestYear=oField.value.substring(4,6);
			}
			if(oField.value.length==8) {
				TestMonth=oField.value.substring(0,2);
				TestDay=oField.value.substring(2,4);
				TestYear=oField.value.substring(4,8);
			}
			if(TestMonth && TestDay) {
				if((TestMonth>0) && (TestMonth<13) && (TestDay>0) && (TestDay<32)) {
					if(TestYear>0) {
						if(TestYear<100) {
							if(TestYear<70) Year=2000 + parseInt(TestYear,10);
							else Year=1900 + parseInt(TestYear,10);
						}
						else Year=TestYear;
					}
					else {
						Year=Today.getFullYear();
					}
					Month=TestMonth;
					Day=TestDay;
				}
			}
		}
		if(!Month) {
			alert("The date you entered is not valid");
			oField.focus();
			oField.select();
		}
		else {
			oField.value=Month + sDelimiter + Day + sDelimiter + Year;
			if (typeof oldonblur == 'function') {
				oldonblur();
			}
		}
	}
	//add code to the field's form's onsubmit event to blur all fields
	//this is necessary because otherwise if someone submits the form by hitting enter
	//in the date field, the onblur event will fire after the form is submitted in IE.
	if(oField.form) {
		var oldFormOnSubmit=oField.form.onsubmit;
		oField.form.onsubmit=function() {
			oField.onblur();
			if(typeof(oldFormOnSubmit=='function') && oldFormOnSubmit) {
				return oldFormOnSubmit();	  
			}
		}
	}
}

// This function registers a form field to be highlighted when it has focus.
// oField - THe field to register
// sColor - The background color to use on focus
function registerFocusField(oField,sColor) {
	var oOnFocus=oField.onfocus;
	var oOnBlur=oField.onblur;
	if(oField.type=="submit") return null;
	if(oField.type=="button") return null;
	//make sure the color has the pound sign
	if(sColor.substr(0,1)!="#") sColor="#" + sColor;
	oField.onfocus=function() {
		if(oField.style.backgroundColor) sLastBGColor=oField.style.backgroundColor;
		else sLastBGColor="#FFFFFF";
		oField.style.backgroundColor=sColor;
		if (typeof oOnFocus == 'function') {
			oOnFocus();
		}
	}
	oField.onblur=function() {
		if(sLastBGColor) {
			oField.style.backgroundColor=sLastBGColor;
		}
		else {
			oField.style.backgroundColor="#FFFFFF";
		}
		sLastBGColor="#FFFFFF";
		if (typeof oOnBlur == 'function') {
			oOnBlur();
		}
	}
	//if(window.attachEvent) oField.attachEvent("onpropertychange",restoreStyles);
}
/*
function restoreStyles(){
	if(event.srcElement.style.backgroundColor != "") event.srcElement.style.backgroundColor = "";
}
*/

// This function registers and stores phone form fields and adds the following
// functionality to them: 1) It prevents non-numeric characters from being typed,
// 2) When the field is full, it automatically tabs to the next field.
// oField - The form field
// sType - 0 = Full Phone, 1 = Area Code, 2 = Prefix, 3 = Suffix
// oNextField - The next field to shift thefocus to when this field is full
function registerPhoneField(oField,sType,oNextField) {
	//if the user did not specify whether this should be an auto-advance field, assume it is
	var oPhone = new Array(oField,sType,oNextField);
	aPhones[aPhones.length]=oPhone;
	//oField.onKeyUp='validatePhoneInput(this)';
	oField.onkeydown=function(e) {
		oField=this;
		if(!e) e=window.event;
		var aPhone=findPhoneField(oField);
		if(!aPhone) return(false);
		if(e.keyCode==46) { //delete
			oField.value="";
		}
		else {
			if( (e.keyCode!=9)) {
				if(aPhone[1]==0) {
					if(((e.keyCode<48) || (e.keyCode>57)) 
							&& (e.keyCode!=96)
							&& (e.keyCode!=97)
							&& (e.keyCode!=98)
							&& (e.keyCode!=99)
							&& (e.keyCode!=100)
							&& (e.keyCode!=101)
							&& (e.keyCode!=102)
							&& (e.keyCode!=103)
							&& (e.keyCode!=104)
							&& (e.keyCode!=105)
							&& (e.keyCode!=110)
							&& (e.keyCode!=189) 
							&& (e.keyCode!=190)
							&& (e.keyCode!=37) 
							&& (e.keyCode!=38) 
							&& (e.keyCode!=39) 
							&& (e.keyCode!=40) 
							&& (e.keyCode!=8)
							&& (e.keyCode!=13)
						) {
						e.returnValue=false;
						return(false);
					}
				}
				else
				{
					if(((e.keyCode<48) || (e.keyCode>57))
							&& (e.keyCode!=96)
							&& (e.keyCode!=97)
							&& (e.keyCode!=98)
							&& (e.keyCode!=99)
							&& (e.keyCode!=100)
							&& (e.keyCode!=101)
							&& (e.keyCode!=102)
							&& (e.keyCode!=103)
							&& (e.keyCode!=104)
							&& (e.keyCode!=105)
							&& (e.keyCode!=110)
							&& (e.keyCode!=189) 
							&& (e.keyCode!=190)
							&& (e.keyCode!=37) 
							&& (e.keyCode!=38) 
							&& (e.keyCode!=39) 
							&& (e.keyCode!=40) 
							&& (e.keyCode!=8)
							&& (e.keyCode!=13)
						) {
						if(!document.all) return(false);
						e.returnValue=false;
					}
				}
			}
		}
	}
	oField.onkeyup=function(e) {
		oField=this;
		if(!e) e=window.event;
		if(!(
			 	(e.keyCode==16) ||
				(e.keyCode==37) ||
				(e.keyCode==38) ||
				(e.keyCode==39) ||
				(e.keyCode==46) ||
				(e.keyCode==8) ||
				(e.keyCode==9) ||
				(e.keyCode==13)
			)) {
			 
			var aPhone=findPhoneField(oField);
			if(!aPhone) return(false);
			if(!aPhone[2] ) return(false);
			var maxLength;
			if(aPhone[1]==1) maxLength=3;
			if(aPhone[1]==2) maxLength=3;
			if(aPhone[1]==3) maxLength=4;
			if(oField.value.length==maxLength) {
				oNextFocus=aPhone[2];
				window.setTimeout("setFocus()",150);
				//aPhone[2].focus();
			}
		}
	}
	oField.onfocus=function() {
		oField.select();
	}
}

function setFocus() {
	if(oNextFocus) oNextFocus.focus();	
	oNextFocus=null;
}

// This function registers and stores numeric fields for use
// in other functions.
// oField - The form field
// bCurrency - Whether or not this field is currency
// bDecimal - Whether or not to allow decimals
function registerNumberField(oField) {
	aNumbers[aNumbers.length]=oField;
	if(arguments.length>1) bCurrency=arguments[1];
	else bCurrency=false;
	if(arguments.length>2) bDecimal=arguments[1];
	else bDecimal=true;
	if(bDecimal) {
		oField.onkeydown=function(e) {
			if(!e) e=window.event;
			if(
				(e.keyCode!=16) &&
				(e.keyCode!=8)  &&
				(e.keyCode!=9)  &&
				(e.keyCode!=37)  &&
				(e.keyCode!=38)  &&
				(e.keyCode!=39)  &&
				(e.keyCode!=40)  &&
				(e.keyCode!=46)  && 
				(e.keyCode!=13)
				&& (e.keyCode!=96)
				&& (e.keyCode!=97)
				&& (e.keyCode!=98)
				&& (e.keyCode!=99)
				&& (e.keyCode!=100)
				&& (e.keyCode!=101)
				&& (e.keyCode!=102)
				&& (e.keyCode!=103)
				&& (e.keyCode!=104)
				&& (e.keyCode!=105)
				&& (e.keyCode!==110) //decimal
				&& (e.keyCode!==190) //decimal
			) {
				if(((e.keyCode<48) || (e.keyCode>57)) && (e.keyCode!=189) && (e.keyCode!=190)) {
					e.returnValue=false;
					return(false);
				}
				if(e.shiftKey || e.altKey || e.ctrlKey) {
					e.returnValue=false;
					return(false);
				}
			}//long if
		}//function
	}
	else {
		oField.onkeydown=function(e) {
			if(!e) e=window.event;
			if(
				(e.keyCode!=16) &&
				(e.keyCode!=8)  &&
				(e.keyCode!=9)  &&
				(e.keyCode!=37)  &&
				(e.keyCode!=38)  &&
				(e.keyCode!=39)  &&
				(e.keyCode!=40)  &&
				(e.keyCode!=46)  && 
				(e.keyCode!=13)
				&& (e.keyCode!=96)
				&& (e.keyCode!=97)
				&& (e.keyCode!=98)
				&& (e.keyCode!=99)
				&& (e.keyCode!=100)
				&& (e.keyCode!=101)
				&& (e.keyCode!=102)
				&& (e.keyCode!=103)
				&& (e.keyCode!=104)
				&& (e.keyCode!=105)
			) {
				if(((e.keyCode<48) || (e.keyCode>57)) && (e.keyCode!=189) ) {
					e.returnValue=false;
					return(false);
				}
				if(e.shiftKey || e.altKey || e.ctrlKey) {
					e.returnValue=false;
					return(false);
				}
			}// long if
		} //function
	} //else
	oField.onfocus=function() {
		try {
			oField.select();
		}
		catch(error) {
			//pass thru
		}
	}
	if(bCurrency) {
		oField.onblur=function() {
			num=oField.value;
			num = num.toString().replace(/\$|\,/g,'');
			if(isNaN(num)) num = "0";
			sign = (num == (num = Math.abs(num)));
			num = Math.floor(num*100+0.50000000001);
			cents = num%100;
			num = Math.floor(num/100).toString();
			if(cents<10) cents = "0" + cents;
			//for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)	num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));
	
			oField.value= (((sign)?'':'-') + num + "." + cents);
		}
	}
}

// registers a military time field
function registerMilitaryTimeField(oField) {
	var oOnBlur=oField.onblur;
	//validate the format when leaving the field
	oField.onblur=function(e) {
		if(oField.value=="") return(true);
		var timeStr=oField.value;
		var sFormat="";
		var timePat = /^(\d{1,2}):(\d{1,2})(:(\d{2}))?$/;
		var matchArray = timeStr.match(timePat);
		if (matchArray != null) {
			hour = matchArray[1];
			minute = matchArray[2];
			second = matchArray[4];
			if (second=="") { second = null; }
			if (hour < 0  || hour > 23) {
				alert("The time you entered is not valid. The hour must be between 0 and 23.");
				oField.focus();
				e.returnValue=false;
				return false;
			}
			if(minute<0) minute=0;
			if (minute<0 || minute > 59) {
				alert ("The time you entered is not valid. The minute must be between 0 and 59.");
				oField.focus();
				e.returnValue=false;
				return false;
			}
			if (second != null && (second < 0 || second > 59)) {
				alert ("The time you entered is not valid. The second must be between 0 and 59.");
				oField.focus();
				e.returnValue=false;
				return false;
			}
			if(hour.length<2) sFormat="0";
			sFormat+=hour+":";
			if(minute.length<2) sFormat+="0";
			sFormat+=minute;
			oField.value=sFormat;
		}
		else {
			alert("The time you entered is not valid.");
			try {
				oField.value="";
				oField.focus();
				e.returnValue=false;
			}
			catch(e) {
				
			}
			return false;
		}
		if (typeof oOnBlur == 'function') {
			return oOnBlur();
		}
		return true;
	}
	oField.onkeydown=function(e) {
		if(!e) e=window.event;
		var charArray = new Array(
		' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-',
		'.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
		'<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
		'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
		'X', 'Y', 'Z', '[', '\\', ']', '^', '_', '0', '1', '2', '3', '4', '5',
		'6', '7', '8', '9', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
		't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', '', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '_', '_', '_', '�', '�', '�', '�', '�', '�', '�',
		'�', '+', '+', '�', '�', '+', '+', '-', '-', '+', '-', '+', '�', '�',
		'+', '+', '-', '-', '�', '-', '+', '�', '�', '�', '�', '�', '�', 'i',
		'�', '�', '�', '+', '+', '_', '_', '�', '�', '_', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'_', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '_', ' ');
		var thisCode=e.keyCode;
		if(thisCode==59) thisCode=58;
		if(
			(thisCode!=16) &&
			(thisCode!=8)  &&
			(thisCode!=9)  &&
			(thisCode!=37)  &&
			(thisCode!=38)  &&
			(thisCode!=39)  &&
			(thisCode!=40)  &&
			(thisCode!=46)  && 
			(thisCode!=13)
		) {
			if((((thisCode<48) || (thisCode>57)) && ((thisCode<96) || (thisCode>105))) && (thisCode!=189) && (thisCode!=190) && (thisCode!=58)) {
				e.returnValue=false;
				return(false);
			}
			if((e.shiftKey || e.altKey || e.ctrlKey) && (thisCode!=58)) {
				e.returnValue=false;
				return(false);
			}
			//test for formatting
			var sNew=""+oField.value + charArray[thisCode-32];
			if(sNew.length==1) {
				if(sNew>2) {
					oField.value="0" + sNew + ":";
					e.returnValue=false;
					return(false);
				}
			}
			else if(sNew.length==2) {
				if(sNew>23) {
					e.returnValue=false;
					return(false);
				}
				else if(sNew.substr(1,1)==":") {
					oField.value="0" + sNew;
					e.returnValue=false;
					return(false);
				}
				else {
					oField.value=sNew + ":";
					e.returnValue=false;
					return(false);
				}
			}
			else if((thisCode==58) && (sNew.length>3)) {
				e.returnValue=false;
				return(false);
			}
		}//long if
	}//function
	
	oField.onfocus=function() {
		try {
			oField.select();
		}
		catch(error) {
			//pass thru
		}
	}
}

// finds this phone in the array
function findPhoneField(oField) {
	var aPhone
	for(i = 0 ; i <aPhones.length ; i++) {
		aPhone=aPhones[i];
		if(aPhone[0]==oField) {
			return(aPhone);
		}
	}
}

// finds this phone in the array
function findDateField(oField) {
	var aDate
	for(i = 0 ; i <aDates.length ; i++) {
		aDate=aDates[i];
		if(aDate[0]==oField) {
			return(aDate);
		}
	}
}

/* Validates a phone number that is broken into 3 fields */
function validateTriPhone(oField1,oField2,oField3,bRequired) {
	// see if we have all values
	if(!oField1.value && !oField2.value && !oField3.value) {
		if(bRequired) return(false);
		else return(true);
	}
	if(oField1.value.length<3) return(false);
	if(oField2.value.length<3) return(false);
	if(oField3.value.length<4) return(false);
	return(true);
}

/* Validates an email address */
function validateEmailAddress(email,required) {
	if(!email && !required) return(true);
	var reEmail = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9\-])+(\.[a-zA-Z0-9_-]+)+$/;
	return(reEmail.test(email));
}

/*Validates a one-field phone number including international phone numbers */
function validateSinglePhone(sValue,bRequired) {
	if(!sValue) {
		if(!bRequired) return(true);
		else return(false);
	}
	if(sValue.length<10) return(false);
	var regPhone=/^(1\s*[\/\.-]?)?(\((\d{3})\)|(\d{3}))\s*([0-9\s./\\-]*)$/;
	return(regPhone.test(sValue));
}

//turns a select box into an auto-complete box similar to a windows combo box.
function registerAutoSelect(oSelect) {
	if(!oSelect.typedLetters) oSelect.typedLetters="";
	oSelect.onkeyup=function(e) {
		oField=this;
		if(!e) e=window.event;
		var charArray = new Array(
		' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-',
		'.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
		'<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
		'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
		'X', 'Y', 'Z', '[', '\\', ']', '^', '_', '0', '1', '2', '3', '4', '5',
		'6', '7', '8', '9', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
		't', 'u', 'v', 'w', 'x', 'y', 'z', '{', '|', '}', '~', '', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'�', '�', '�', '�', '_', '_', '_', '�', '�', '�', '�', '�', '�', '�',
		'�', '+', '+', '�', '�', '+', '+', '-', '-', '+', '-', '+', '�', '�',
		'+', '+', '-', '-', '�', '-', '+', '�', '�', '�', '�', '�', '�', 'i',
		'�', '�', '�', '+', '+', '_', '_', '�', '�', '_', '�', '�', '�', '�',
		'�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�',
		'_', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '_', ' ');
		//window.status="Code: " + e.keyCode;
		if(e.keyCode < 32 || e.keyCode > 255) {
			this.typedLetters="";
			//allow arrow keys, not others
			if(e.keyCode<37 || e.keyCode>40) {
				e.returnValue=false;
				return(false);
			}
			else {
				return(true);
			}
		}
		var charTyped= charArray[e.keyCode-32];
		var searchText=this.typedLetters + charTyped;
		var checkText="";
		var bFound=false;
		for(i = 0 ; i < this.options.length ; i ++) {
			checkText=this.options[i].text.substr(0,searchText.length);
			if(checkText.toLowerCase()==searchText.toLowerCase()) {
				//this.options.length+=1;
				//this.options[this.options.length-1].text="Searching for " + searchText;
				this.selectedIndex=i;
				i=this.options.length;
				bFound=true;
				this.typedLetters+=charTyped;
			}
		}
		e.returnValue=false;
		e.cancelBubble=true;
		return(false);
	}
	
	var oldonkeydown=oSelect.onkeydown;
	oSelect.onkeydown=function(e) {
		if(!e) e=window.event;
		if(typeof oldonkeydown=="function") oldonkeydown();
		if((e.keyCode >= 32) && (e.keyCode <= 255) && (e.keyCode!=37) && (e.keyCode!=38) && (e.keyCode!=39) && (e.keyCode!=40)) {
			e.returnValue=false;
			e.cancelBubble=true;
			return(false);
		}
	}
	
	var oldonfocus=oSelect.onfocus;
	oSelect.onfocus=function(e) {
		oSelect.typedLetters="";
		if(typeof oldonfocus=="function") return oldonfocus();
	}
	
	var oldonblur=oSelect.onblur;
	oSelect.onblur=function(e) {
		oSelect.typedLetters="";
		if(typeof oldonblur=="function") return oldonblur();
	}
}