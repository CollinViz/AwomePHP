// method that sets up a cross-browser XMLHttpRequest object
function getHTTPObject() {
	var http_object;
	// MSIE Proprietary method
	/*@cc_on
	@if (@_jscript_version >= 5)
		try {
			http_object = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			try {
				http_object = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (E) {
				http_object = false;
			}
		}
	@else
		xmlhttp = http_object;
	@end @*/
	// Mozilla and others method
	if (!http_object && typeof XMLHttpRequest != 'undefined') {
		try {http_object = new XMLHttpRequest();}
		catch (e) {http_object = false;}
	}
	return http_object;
}
var objHTTP = getHTTPObject(); // We create the HTTP Object

function MM_findObj(n, d) { //v3.0
	if(!d) d=document;
	if(d.getElementById(n)){	
		 return d.getElementById(n);
	}else{
		if(d.getElementByName){
			return d.getElementByName(n);
		}else{
			return null;
		}
	}
	/*	
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}*/
   
}
function MM_validateForm() { //v3.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) {
	test=args[i+2]; val=MM_findObj(args[i]);
    if (val) {nm=val.name;if ((val=val.value)!="") {
			if (test.indexOf('isEmail')!=-1) { 
				p=val.indexOf('@');if (p<1 || p==(val.length-1))errors+='- '+nm+' must contain an e-mail address.\n';
			} else if(test.indexOf('inCRange')!=-1) {p=test.indexOf(':');
				min=test.substring(8,p);max=test.substring(p+1);num = val.length;if(num<min || num>max)
					errors+='- '+nm+' must contain a length between '+min+' and '+max+'.\n';
			} else if (test!='R') {num = parseFloat(val);if (val!=''+num)errors+='- '+nm+' must contain a number.\n';
				if (test.indexOf('inRange') != -1) {p=test.indexOf(':');
					min=test.substring(8,p);max=test.substring(p+1);if (num<min || max<num)
					errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
			}
		}
	} else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } 
  document.MM_returnValue = (errors == '');
}
function validatthis(box,type,errormsg) {
	var val;
	if (document.MM_returnValue == true){
		MM_validateForm(box,'',type);if (document.MM_returnValue == false){
			alert(errormsg);val=MM_findObj(box);val.focus();
		}
	}
}

/****************************************************
     Author: Eric King
     Url: http://redrival.com/eak/index.shtml
     This script is free to use as long as this info is left in
     Featured on Dynamic Drive script library (http://www.dynamicdrive.com)
****************************************************/
var win=null;

function NewWindow(mypage,myname,w,h,scroll,pos){
	if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
		TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	}if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;
		TopPosition=(screen.height)?(screen.height-h)/2:100;
	}else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;
		TopPosition=20;
	}
	settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';	
	return window.open(mypage,myname,settings);
}
function deleteme(name,url) {
	if(confirm("Are you sure you want to delete " + name + " ?") ==true ){
		window.open(url,"_self");}
}


document.__timerX = -1;
function __debugurl(){
	window.clearInterval(document.__timerX);
	document._IsGubeddebug = '1';
	_onaction('__ondebug()');
}

function _debugWindow(mypage,myname,w,h,scroll,pos){
	LeftPosition=((screen.width)?(screen.width-w):100)-10;
	TopPosition=((screen.height)?(screen.height-h):100)-50;	
	settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';	
	return window.open(mypage,myname,settings);
}

function __hideshowhtml(HtmlObjName,HidShow){
	var htmlObjList = MM_findObj(HtmlObjName);
	var htmlObj;
	if(htmlObjList){
		if(!htmlObjList.length){
			htmlObjList.style.display=(HidShow=="hide"?'none':'');
		}else{
			for(i=0;i<htmlObjList.length;i++){
				htmlObjList[i].style.display=(HidShow=="hide"?'none':'');
			}
		}		
	}
}

function __centerObject(HtmlObjName){
	objwndBrows = MM_findObj(HtmlObjName);
	var displaywidth = document.body.clientWidth;
	var displayheight =document.body.clientHeight;
	var heightObj = objwndBrows.clientHeight; //Number(objwndBrows.style.height.substr(0,objwndBrows.style.height.length-2))
	var widthObj = objwndBrows.clientWidth; //Number(objwndBrows.style.width.substr(0,objwndBrows.style.width.length-2))
	objwndBrows.style.top = (displayheight/2) - (heightObj/2);
	objwndBrows.style.left = (displaywidth/2) - (widthObj/2);
}


function __setSelectboxOptions(htmlObj,value,Datadeliminater,KeyDeliminater,SelectedKey){
	var htmlObjList = MM_findObj(htmlObj);
	if(htmlObjList){		
		//if(!htmlObjList.innerHTML) //exit if not HTML select
		//	return;
		htmlObjList.innerHTML="";
		arData = value.split(Datadeliminater);
		if(KeyDeliminater==""){
			for(i=0;i<arData.length;i++){	
				if(arData[i]!=''){
					__addItemToSelect(htmlObjList,arData[i],arData[i],SelectedKey);					
					//i++;
				}
			}
		}else{
			
			for(i=0;i<arData.length;i++){
				if(arData[i]!=''){  
					arTextAndValue = arData[i].split(KeyDeliminater); 
					if(arTextAndValue.length>1){ 
						__addItemToSelect(htmlObjList,arTextAndValue[0],arTextAndValue[1],SelectedKey);
					}else{
						__addItemToSelect(htmlObjList,arTextAndValue[0],arTextAndValue[0],SelectedKey);
					}
					
				}
			}
		}
	}

}


function __addItemToSelect(SelectObj,Value,text,SelectedKey){	
	var oOption = document.createElement("OPTION");		
	oOption.text = text;
	oOption.value = Value;
	if(!document.all){
		SelectObj.options.add(oOption,null);
	}else{
		SelectObj.options.add(oOption);
	}
	if(SelectedKey ==Value){
		oOption.selected= true;		
	}
}
function __CX(){
	alert("dsknsdnsfdnvkfnvfl");
}
function __updateHtmlObjEvent(htmlObj,EventName,functionDef){
	obj = MM_findObj(htmlObj);
	
	if(obj){
		if (obj.addEventListener){
		
			obj.addEventListener(EventName, functionDef, false);
		  	return true;
		} else if (obj.attachEvent){
		
		  	var r = obj.attachEvent("on"+EventName, functionDef);
		  	return r;
		} else {
		  	return false;
		}
	 }
}

function __updateElement(htmlObj,ElementName,NewValue){
	document.__varhtmlObjList = MM_findObj(htmlObj);
	if(document.__varhtmlObjList){
		if(!document.__varhtmlObjList.length){
			$(document.__varhtmlObjList).attr(ElementName,NewValue);
		}else{
			for(i=0;i<document.__varhtmlObjList.length;i++){
				document.__varhtmlObjList[i].setAttribute(ElementName,NewValue,1);
			}
		}		
	}
}
function __updateobjvalue(HtmlObjName,NewValue){
	var htmlObjList = MM_findObj(HtmlObjName);
	var htmlObj;
	if(htmlObjList){
		if(!htmlObjList.length){
			__setUpdateObj(htmlObjList,NewValue);
		}else{
			if(htmlObjList.tagName.toLowerCase()=="select"){
				__setUpdateObj(htmlObjList,NewValue);
			}else{ 
				for(i=0;i<htmlObjList.length;i++){
					__setUpdateObj(htmlObjList[i],NewValue);
				}
			}
		}		
	}
}

function __setUpdateObj(htmlObj,NewValue){ 
	switch(htmlObj.tagName.toLowerCase()){			
			case "input":				
				switch(htmlObj.type.toLowerCase()){
					case 'text': case 'password': case 'hidden': case 'textarea': case 'submit' : case 'button' :
						htmlObj.value = NewValue;break;
					case 'checkbox':
						if(NewValue.toLowerCase()=="true"){
							htmlObj.checked =true;
						}else{
							htmlObj.checked =false;
						}						
						break;
					case 'radio':
						if(htmlObj.value==NewValue){
							htmlObj.checked =true;
						}
						break;
					case '':
						break;
						
				}
				break;
			case "option":
				//alert(htmlObj.value);
				break;
			case "select":			
				for(z = 0;z<htmlObj.options.length;z++){
					if(htmlObj.options[z].value.toLowerCase()==NewValue.toLowerCase()){
						htmlObj.options[z].selected=true;}}
				break;
			default : 
				htmlObj.innerHTML = NewValue;
				break;
		}
}




function _ajaxdone(){
	var strSTARTPHP = "@@@START_PHPVIEWAJAX@@@";
	var strENDPHP = "@@@END_PHPVIEWAJAX@@@";
	if (objHTTP.readyState == 4) {
		
		strResult=objHTTP.responseText;
		if(document.__Hasdebugwin==true ){if(document.__debugwin.close){MM_findObj("txtajax",document.__debugwin.document).value=strResult;}}
		
		//alert(strResult);
		strMessageBox = "";
		intStartPos = strResult.indexOf(strSTARTPHP);
		intEndPos = strResult.indexOf(strENDPHP);
		if(intStartPos>=0){
			strEvaleBuffer = strResult.substr(intStartPos+strSTARTPHP.length,intEndPos-(intStartPos+strSTARTPHP.length));
			eval(strEvaleBuffer);			
			if(intStartPos>0)
				strMessageBox = strResult.substr(0,intStartPos);
		}else{
			strMessageBox = strResult;
		}
		if(jQuery.trim(strMessageBox)!=""){
			alert(strMessageBox);
		}
		 
		if(window.onAjaxDone){
			window.onAjaxDone();		
		}
		
	}
	
}
function _ajaxdone_return($UnknowBuffer){
	var strSTARTPHP = "@@@START_PHPVIEWAJAX@@@";
	var strENDPHP = "@@@END_PHPVIEWAJAX@@@";
	var aBuffer = new Array();;
		
	strResult=$UnknowBuffer;
	//if(document.__Hasdebugwin==true ){if(document.__debugwin.close){MM_findObj("txtajax",document.__debugwin.document).value=strResult;}}
	
	//alert(strResult);
	strMessageBox = "";
	intStartPos = strResult.indexOf(strSTARTPHP);
	intEndPos = strResult.indexOf(strENDPHP);
	if(intStartPos>=0){
		strEvaleBuffer = strResult.substr(intStartPos+strSTARTPHP.length,intEndPos-(intStartPos+strSTARTPHP.length));
		aBuffer[0] = strEvaleBuffer;			
		if(intStartPos>0)
			strMessageBox = strResult.substr(0,intStartPos);
	}else{
		strMessageBox = strResult;
	}
	if(jQuery.trim(strMessageBox)!=""){
		aBuffer[1] = strMessageBox;
	}else{
		aBuffer[1] ="";
	}
	 
	if(window.onAjaxDone){
		window.onAjaxDone();		
	}
	return aBuffer;
}
 
//AngulerJS AJAX POST
function _AJS_onaction($http,$scope,$functionName,$PostData,$CallBack){
	 
	$("#ajaxprogress").show();
	$("#ajaxprogress").center();
	if(Object.prototype.toString.call( $PostData ) === '[object Object]'){
		$PostData.__call=$functionName;
		$PostData.__class=document.__classname;
	}else{
		$PostData={"__class" :document.__classname,								 
					"__call":$functionName};
	}
	
	$scope.status="....";
	$http.post(document.__ajaxpath, $PostData).
			success(function(data, status) { 
				$scope.status = status;
				$scope.data = data;
				$scope.result = data; // Show result from server in our <pre></pre> element
				if(status>=200){ 
					$aAjax = _ajaxdone_return(data);             
					eval($aAjax[0]);
					$scope.status="Saved";
					
					if($CallBack){
						$CallBack();
					}
					$("#ajaxprogress").hide();
					if($aAjax[1]!=""){
						alert($aAjax[1]);
						/*jQuery("#frmMessage").attr('title','Alert message');
						jQuery("#frmMessage").text($aAjax[1]);
						jQuery("#frmMessage").dialog({autoOpen: false,
													modal: true, 
													buttons: { "Ok": function() { 
																$(this).dialog('close'); 
															}  
												} });
						$('#frmMessage').dialog('open');*/
					}	
				}
			})
			.
			error(function(data, status) { 
				$scope.data = data || "Request failed";
				$scope.status = "Error";        
			});  
	
}


function _onajxaction(functionName){
	 
	if(window.onAjaxSend){
		window.onAjaxSend();
	}
	
	//alert(functionName + " " + document.__ajaxpath);
	objHTTP.open('POST',document.__ajaxpath, true);
	objHTTP.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// where to go
	objHTTP.onreadystatechange = _ajaxdone;
	__call = MM_findObj("__call");	__call.value=functionName;	
	var colFrom = document.getElementsByTagName("form");
	$strAJECBuffer = "__class="+document.__classname+"&";
	if(colFrom.length> 0){
		__selectectupdate(colFrom[0]);
		for(i=0;i<colFrom[0].elements.length;i++){
			el = colFrom[0].elements[i];
			//alert(el.tagName);
			switch(el.type) { 
				case 'text': case 'password': case 'hidden': case 'textarea': 
					$strAJECBuffer+=encodeURIComponent(el.name)+"="+encodeURIComponent(el.value)+"&";
					break;					
				case 'select-one': 
					if (el.selectedIndex>=0) { $strAJECBuffer+=encodeURIComponent(el.name)+"="+encodeURIComponent(el.options[el.selectedIndex].value)+"&"; } break; 
				case 'select-multiple': for (var j=0; j<el.options.length; j++) { if (el.options[j].selected) { $strAJECBuffer+=encodeURIComponent(el.name)+"="+encodeURIComponent(el.options[j].value)+"&"; } } break; 
				case 'checkbox': case 'radio': if (el.checked) { $strAJECBuffer+=encodeURIComponent(el.name)+"="+encodeURIComponent(el.value)+"&"; } break; }
			
		}
	}
	$strAJECBuffer+="__OUTPUTTYPE=AJAX"; 
	objHTTP.send($strAJECBuffer);


}
function __createSelectText(SelectObj,FormObj){
	if(!MM_findObj(SelectObj.name+"_TEXT")){
		var oNewNode = document.createElement("div");
		FormObj.appendChild(oNewNode);
		if(SelectObj.selectedIndex>=0){
			Selectvalue = SelectObj.options[SelectObj.selectedIndex].text;
			oNewNode.innerHTML="<input name=\""+SelectObj.name+"_TEXT\" type=\"hidden\" id=\""+SelectObj.name+"_TEXT\" value=\""+Selectvalue+"\"> ";
		}
	}
}
function __selectectupdate(FormObj){
	arselect  = document.getElementsByTagName("select");
	if(arselect.length){
		for(i=0;i<arselect.length;i++){
			__createSelectText(arselect[i],FormObj);
		}
	}
}
function _onaction(functionName){
	ActionPage="";
	if(document.__Hasdebugwin==true ){if(document.__debugwin.close){document.__debugwin.close();}}
	args=_onaction.arguments;__call = MM_findObj("__call");	__call.value=functionName;
	var colFrom = document.getElementsByTagName("form");	
	if(colFrom.length){
		
		__selectectupdate(colFrom[0]);
		
		if(args.length>=3){
			ActionPage =args[2];
			if(ActionPage!='' && ActionPage!='underfined'){
				colFrom[0].action=ActionPage;
			}
		}
		if(args.length>=4){
			strtarget =args[3];
			if(strtarget!='' && strtarget!='underfined'){
			colFrom[0].target=strtarget;
			}
		}
	if(document._IsGubeddebug=='1'){if(ActionPage!=""){colFrom[0].action = document.__serverurl+ ActionPage;}if(colFrom[0].action==''){	colFrom[0].action = window.location.href;}if(colFrom[0].action.substr(0,document.__GubeddebugUrl.length).toUpperCase()== document.__GubeddebugUrl.toUpperCase()){colFrom[0].action = colFrom[0].action.substr(document.__GubeddebugUrl.length,colFrom[0].action.length);
			}MM_findObj("__IsGubeddebug").value='1';colFrom[0].action = document.__GubeddebugUrl + colFrom[0].action;}
	colFrom[0].submit();}
}
function __onload(){	
	if(window.onload){var colFrom = document.getElementsByTagName("form");		
		if(colFrom.length){colFrom[0].onload=__loadme();}}else{window.onload=__loadme;}
}
function __loadme(){
	var inputfoucasedone = false;
	var colFrom = document.getElementsByTagName("form");
	if(!document._IsGubeddebug){document._IsGubeddebug='';}
	
	if(colFrom.length){var oNewNode = document.createElement("div");
		colFrom[0].appendChild(oNewNode);
		oNewNode.innerHTML="<input name=\"__viewstate\" type=\"hidden\" id=\"__viewstate\"> " + 
				   "<input name=\"__call\" type=\"hidden\" id=\"__call\"><input name=\"__IsGubeddebug\" type=\"hidden\" id=\"__IsGubeddebug\">";
	    
		for(i=0;i<colFrom[0].elements.length-1;i++){
			if(colFrom[0].elements[i].tagName.toLowerCase()=="input"){				
		   		if(colFrom[0].elements[i].type=="text" && colFrom[0].elements[i].style.display=='' && inputfoucasedone==false){
		      	//colFrom[0].elements[i].focus();
		      	inputfoucasedone = true;
		      	//return;
		   		}
			}else if(colFrom[0].elements[i].tagName.toLowerCase()=="select" ){
				if(colFrom[0].elements[i].lastseleced){
					if(colFrom[0].elements[i].lastseleced!=""){
						__setUpdateObj(colFrom[0].elements[i],colFrom[0].elements[i].lastseleced);
						i++;
						colFrom[0].elements[i].lastseleced="";
					}
				}
			}
			
		}
	}else{
		      var colbody = document.getElementsByTagName("body");
		      if(colbody.length){var oNewNode = document.createElement("form");
		colbody[0].appendChild(oNewNode);oNewNode.method="post";__loadme();}
		      }	
}


window.__isviewstate=true;

jQuery.fn.center = function(parent) {
    if (parent) {
        parent = this.parent();
    } else {
        parent = window;
    }
    this.css({
        "position": "absolute",
        "top": ((($(parent).height() - this.outerHeight()) / 2) + $(parent).scrollTop() + "px"),
        "left": ((($(parent).width() - this.outerWidth()) / 2) + $(parent).scrollLeft() + "px")
    });
return this;
}
