function remove_documents(){
	totalCheck=document.getElementById('totalrecord').value;
	var sn="";
	for(i=1;i<=totalCheck;i++){
		//alert(document.getElementById('doc'+i).checked);
		if(document.getElementById('doc'+i).checked==true){
			
			sn=document.getElementById('c'+i).value+"|";
		}
	}
	remove_document(sn);
}
function remove_document(sn){
	if(sn!=""){
		
	var opt=confirm("Do you really want to remove the(se) document(s)?");
		if(opt==true){
			//alert(sn);
			ajax_module('ajax/ajaxscripts.php?sn='+sn+'&fxn=removedoc','r');
			window.location.href='dashboard.php'
		}
	}
}

function mvc_remove_documents(){
	totalCheck=document.getElementById('totalrecord').value;
	//totalCheck=4;
	var sn="";
	for(i=1;i<=totalCheck;i++){
		//alert(document.getElementById('d'+i).checked);
		if(document.getElementById('d'+i).checked==true){
			
			sn=sn+document.getElementById('c'+i).value+"|";
		}
	}
	mvc_remove_document(sn);
}
function mvc_remove_document(sn){
	if(sn!=""){
		
	var opt=confirm("Do you really want to remove the(se) document(s)?");
		if(opt==true){
			//alert(sn);
			ajax_module('/delete?sn='+sn,'r');
			setTimeout(redirect,1000); // because of the speed at which the script is processed, a minor delay is esterblished before refreshing of the page.
			
		}
	}
}
function redirect(){
	window.location.href='/dashboard';
}
function ajax_module(file,div){

    $.ajax({url: file, success: function(result){
     $("#"+div).html(result);
    },dataType:'text'});
}
function data_protected_ajax_module(file,div,email,psw){

    $.ajax({url: file,type:'POST', success: function(result){
     $("#"+div).html(result);
    },dataType:'text',
    data:{'email':email,'psw':psw}
    });
}