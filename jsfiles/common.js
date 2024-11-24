// JavaScript Document

//Depreciated function which used for click element automatically
function clickElement(elementid){
    var e = document.getElementById(elementid);
    if (typeof e == 'object') {
        if(document.createEvent) {
            var evObj = document.createEvent('MouseEvents');
            evObj.initEvent('click',true,true);
            e.dispatchEvent(evObj);
            return false;
        }
        else if(document.createEventObject) {
            e.fireEvent('onclick');
            return false;
        }
        else {
            e.click();
            return false;
        }
    }
}
// function to use schedule the test cases in a pop window
function popWindow(wName){
	var count=0;
	$('.optionSelect option').each(function(i){
	$(this).attr("selected", "selected");
	count+=1;
	});
	if(count==0){
	alert("Please select at least one testcase");
	return false;
	}
	var str = $("form").serialize();
	var constr=unescape(str);
	var features = 'width=1000,height=1000,toolbar=no,location=no,directories=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no';
	var urlStr=wName+'?data=itemselect[]=temp&'+constr;
	window.open(urlStr,wName,features);
}

// Depreciated function which used for post data with checked schedule
function schedule_chk(){
	var cnt=0;
	if(document.caseform.schedule_bt.checked){
	document.caseform.action ="scheduleUI.php";	
	$('.optionSelect option').each(function(i){
		$(this).attr("selected", "selected");
		cnt+=1;
	});
	if(cnt==0){
	alert("Please select at least one testcase");
	return false;
	}
	document.caseform.submit();
	}else{
		return false;
	}
}

// function using for move test cases from right to left combo
function fnMoveItems(lstbxFrom, lstbxTo) {
		//alert("aaaa");
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
        if ((varFromBox != null) && (varToBox != null)) {
            if (varFromBox.length < 1) {
                alert('There are no items in the source ListBox');
                return false;
            }
            if (varFromBox.options.selectedIndex == -1) // when no Item is selected the index will be -1
            {
                alert('Please select an Item to move');
                return false;
            }
            while (varFromBox.options.selectedIndex >= 0) {
                var newOption = new Option(); // Create a new instance of ListItem 

                newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text;
                newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value;
				newOption.selected = varFromBox.options[varFromBox.options.selectedIndex].selected;
                varToBox.options[varToBox.length] = newOption; //Append the item in Target Listbox

                varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 

            }
        }
        return false;
}

// function using for move test cases from left to right combo
function fnAddItems(lstbxFrom, lstbxTo) {
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
        if ((varFromBox != null) && (varToBox != null)) {
            if (varFromBox.length < 1) {
                alert('There are no items in the source ListBox');
                return false;
            }
            if (varFromBox.options.selectedIndex == -1) // when no Item is selected the index will be -1
            {
                alert('Please select an Item to move');
                return false;
            }
            while (varFromBox.options.selectedIndex >= 0) {
                var newOption = new Option(); // Create a new instance of ListItem 

                newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text;
                newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value;
                varToBox.options[varToBox.length] = newOption; //Append the item in Target Listbox

                varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 

            }
        }
        return false;
}

//function using for sorting test cases

function sortTestcase(arraysortTestCase){
	var arraysort=new Array();
	for(var i=0;i<arraysortTestCase.length;i++){
		 var testCase=new Array();
		 testCase=arraysortTestCase[i].split("-");
		 arraysort[i]=testCase[1]-0;
	}
	for(var x = 0; x <arraysort.length; x++) {
	  for(var y = x; y < arraysort.length; y++) {
		if(arraysort[x] < arraysort[y]) {
		  var hold = arraysort[x];
		  arraysort[x] = arraysort[y];
		  arraysort[y] = hold;
		}
	  }
	}
	var returearray=new Array();
	for(var j=0;j < arraysort.length;j++){
		 returearray[j]="VMGUI-"+arraysort[j];
	}
	return returearray;
}

// Depricated function used for clean name text box
function changeName(){
	$('input[name=uname]').attr('value', "");
}

//function using for unchecked the check boxes
function unchecked() {
	$('input[name=itemSelect[]]').attr('checked', false);
}


// This is main function which using for DOM manipulation bkp-24-04-204
/*function callExecute(){
	var str = $("form").serialize();
	var constr=unescape(str);
	$.ajax({
	type: "POST", 
	url: 'runTestCase.php', 
	data: constr,  
	complete: function(txt){ 
		$("#testCaseDis").html(txt.responseText);  
	}
	});
	return false;
}*/

function callExecute(){
	
	// Perform Validation
	
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $('input[name="emergency_email"]').val();
		if(email !=""){				
				var result = email.split(",");				
    			for(var i = 0;i < result.length;i++)				
    			if (!filter.test(result[i])){ 
            		alert('The email address is not valid');						
						return false; 
						   		
				}
			}
	
	$(document).ready(function(e) {
		err=0;
        if ($('input[name^=itemSelect]:checked').length == 0) {
        	alert("Please select at least one test suite");
			err++;	
			return false;		
    	}
		/*err=0;
		$("input[name^=itemSelect]:checked").each(function(){    		
			var cb_val = $(this).val();
			var order = cb_val.split(':');
			var testcase = order[0];
			var testcase_sr = order[1];
			var tc_file_selected = $("select[name='select_"+testcase_sr+"2']").length;
			if(tc_file_selected !=0){
				alert("Please select test case for "+testcase);
				err++;
				return false;
				}
		});*/
		
    });
	
	//alert(err);
	// End Perform Validation	
	if(err==0){
		var str = $("form").serialize();
		var constr=unescape(str);
		$.ajax({
		type: "POST", 
		url: 'runTestCase.php', 
		data: constr, 
		beforeSend: function() {
			$("#imgdis").css('display','inline');
			$('#submit_test').attr("disabled", "disabled");
			$('#submit_abort').removeAttr("disabled");			
			},		
		complete: function(txt){ 
			$("#testCaseDis").html(txt.responseText); 
			$("#imgdis").css('display','none');
			$('#submit_test').removeAttr("disabled");	
			$('#submit_abort').attr("disabled", "disabled");
		}
		});
		return false;
	}
}





// This is main function which using for DOM manipulation
function callAbort(){
	var str = $("form").serialize();
	var constr=unescape(str);
	//alert(constr)
	//return false;
	$.ajax({
	type: "POST", 
	url: 'killProc.php', 
	data: constr,
	beforeSend: function() {
			$("#imgdis").css('display','none');			
			$("#imgdis_abort").css('display','inline');			
			//$('#submit_test').attr("disabled", "disabled");			
			},	  
	complete: function(txt){
	//alert("suman"+txt.responseText)
	$("#imgdis_abort").css('display','none');
	$("#testCaseDis").html(txt.responseText);
	}
	});
}

function updateConfig(){	
	var str = $("form").serialize();
	var constr=unescape(str);
	$.ajax({
	type: "POST", 
	url: 'updateConfig.php', 
	data: constr,  
	complete: function(txt){ 
		$("#testCaseDis").html(txt.responseText);  
	}
	});
	return false;
}






// This is main function which using for DOM manipulation
$(document).ready(function() {
	var count=0;
	//$('pre').hide();
	
	$(".itemSelect").live("click", function() {
		//alert("hi");return;
		var selectedItems = new Array();
		var unselectedItems =new Array();
		$("input[name='itemSelect[]']:checked").each(function() {selectedItems.push($(this).val());});
		$("input[name='itemSelect[]']:not(:checked)").each(function() {unselectedItems.push($(this).val());})
		if (selectedItems.length == 0){
			uncheckedItem=unselectedItems[0].replace('\/','');
			$('#'+uncheckedItem).hide();
		}else{
			for(var i=0;i<selectedItems.length;i++){
			var checkedItem="";
			checkedItem=selectedItems[i].replace('\/','');
			$('#'+checkedItem).show();
			}
		}
		for(var i=0;i<unselectedItems.length;i++){
		var uncheckedItem="";
		uncheckedItem=unselectedItems[i].replace('\/','');
		$('#'+uncheckedItem).hide();
		}
		});
		
			
	$('#caseform').submit(function(e){
	$('.optionSelect option').each(function(i){
	$(this).attr("selected", "selected");
	count+=1;
	});
	if(count==0){
	alert("Please select at least one testcase");
	return false;
	}
		
		
		/*if(count>20){
		alert("You have selected "+count+" testcases.It might take several miniutes to complete the cycle.The report link will be dispatched to the email address.It is recommended to close the browser");
		}*/
		var selectedItems = new Array();
		var unselectedItems =new Array();
		$("input[name='itemSelect[]']:checked").each(function() {selectedItems.push($(this).val());});
		var htmlStr="";
		var checkedItem="";
		for(var i=0;i<selectedItems.length;i++){
		checkedItem=selectedItems[i].replace('\/','')+','+checkedItem;
			var frominput=$('#frominput').val();
			var toinput=$('#toinput').val();
			var difference=toinput-frominput;
			if(selectedItems[i].replace('\/','')=='Parameter'){
				if(parseInt(difference) > 39){
					//alert("from="+frominput);
//					alert("to="+toinput);
					alert("Difference not more than 40");
					return false;
				}
				if(parseInt(frominput) > parseInt(toinput)){
					//alert("from="+frominput);
//					alert("to="+toinput);
					alert("From value can not be greater than To value");
					return false;
				}
			}
		}
		//alert("You have selected "+count+" testcases.It might take several miniutes to complete the cycle.The report link will be dispatched to the email address.You will now be redirecting to the home page");
		//return false;
		var lencheckedItem=(checkedItem.length-1);
		var newcheckedItem=checkedItem.substr(0,lencheckedItem);
		var testcaseItem=new Array();;
		$('.optionSelect option').each(function(i){
		testcaseItem.push($(this).val());
		});
		testcaseItem=sortTestcase(testcaseItem);
		var Items="";
		for(var i=0;i<testcaseItem.length;i++){
		Items=testcaseItem[i]+"     Executing <br>"+Items;
		}
		/*htmlStr="<br>Test Suites are Executing - "+newcheckedItem+"<br><br>"+Items;
		$('#testCaseDis').html(htmlStr);
		$('#imgdis').show();*/
		});
		//callExecute();
		//return false;
		
});

//fuhnction using for close the window
function closeWin(){
window.close();
}

// function using for trim the inputs
function trim(str)
{
    //if(!str || typeof str != 'string')
        //return null;
    return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}
// function using for populate vmg existing info
function loadvmg(objVal){
	$.ajax({  
	type: "POST", 
	url: 'readVmg.php', 
	data: "name="+objVal.value,  
	complete: function(html){ 
	$("#targetDiv").html(html.responseText);  
	}  
	});  
}
// function using for validates empty for schedule form
function validation(){
var doc=document.scheduleform;
	if(doc.conffile.value==""){
	 	alert("Please select VMG box");
		doc.conffile.focus();
		return false;
	}	
	if(trim(doc.from_date_time.value)==""){
	 	alert("From Date Time can't be not blank");
		doc.from_date_time.focus();
		return false;
	}
}
// function using for (jquery Ajax) submit data to write schedule
function schedulerSubmit(){
	if(validation()==false){
	return false;
	}
	var str = $("form").serialize();
	var constr=unescape(str);
	$.ajax({
	type: "POST", 
	url: 'writeSchedule.php', 
	data: constr,  
	complete: function(txt){  
		$("#resultDiv").html(txt.responseText);  
	}
	});
}
// function using for (jquery Ajax) TCM and Channel combo select
function comboSelect(){
$('#notcm').chainSelect('#nochannel','combobox.php',
	{ 
		before:function (target) //before request hide the target combobox and display the loading message
		{ 
			//$("#loading").css("display","block");
			$(target).css("display","none");
		},
		after:function (target) //after request show the target combobox and hide the loading message
		{ 
			//$("#loading").css("display","none");
			$(target).css("display","inline");
		}
	});	
}
//function using for (jquery Ajax) show loading image
function showLodingImage(){
		var Items=$('input:hidden').val();
		var testSuite="ChannelExtra";
		htmlStr="<br>Test Suites are Executing - "+testSuite+"<br><br>"+Items;
		$('#testCaseDis').html(htmlStr);
		$('#imgdis').show();	
}

// function using for move all test cases from right to left combo
function fnSelectAllItems(lstbxFrom, lstbxTo) {
		//alert("aaaa");
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
		if (varFromBox.length < 1) {
			alert('There are no items in the source ListBox');
			return false;
		}
		var lenLeftBox=varFromBox.length;
		for(var l=0;l<lenLeftBox;l++){
			/*alert(l)
			alert(varFromBox.options[l].text)*/
			var newOption = new Option(); // Create a new instance of ListItem 
			newOption.text = varFromBox.options[l].text;
			newOption.value = varFromBox.options[l].value;
			newOption.selected = varFromBox.options[0].selected;
			varToBox.options[l] = newOption; //Append the item in Target Listbox

			//varFromBox.remove(varFromBox.options[l]); //Remove the item from Source Listbox 
		}
		for(var l=0;l<lenLeftBox;l++){
			varFromBox.remove(varFromBox.options[l]); //Remove the item from Source Listbox
			//alert(varToBox.options[l].value);
			varToBox.options[l].selected=true;
		}
        return false;
}

// function using for move all test cases from left to right combo
function fnDeSelectAllItems(lstbxTo,lstbxFrom) {
		//alert("aaaa");
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
		if (varFromBox.length < 1) {
			alert('There are no items in the destination ListBox');
			return false;
		}
		var lenLeftBox=varFromBox.length;
		for(var l=0;l<lenLeftBox;l++){
			/*alert(l)
			alert(varFromBox.options[l].text)*/
			var newOption = new Option(); // Create a new instance of ListItem 
			newOption.text = varFromBox.options[l].text;
			newOption.value = varFromBox.options[l].value;
			newOption.selected = varFromBox.options[0].selected;
			varToBox.options[l] = newOption; //Append the item in Target Listbox

			//varFromBox.remove(varFromBox.options[l]); //Remove the item from Source Listbox 
		}
		for(var l=0;l<lenLeftBox;l++){
			varFromBox.remove(varFromBox.options[l]); //Remove the item from Source Listbox
			//alert(varToBox.options[l].value);
			varToBox.options[l].selected=false;
		}
        return false;
}
