// JavaScript Document
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

// This is main function which using for DOM manipulation
function callExecute(){
	//alert("test");
	//return false;
	var str = $("form").serialize();
	var constr=unescape(str);
	$.ajax({
	type: "POST", 
	url: 'runChannelTestCase.php', 
	data: constr,  
	complete: function(txt){ 
		$("#testCaseDis").html(txt.responseText);  
	}
	});
	return false;
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
	complete: function(txt){
	//alert("suman"+txt.responseText)
	$("#testCaseDis").html(txt.responseText);
	}
	});
}