var StmtNo = 1;
var RowNo = 1;


// Display the popup in center
function positionPopup(){
    if(!$("#modal").is(':visible')){ return; }
    $("#modal").css({
        left: ($(window).width() - $('#modal').width()) / 2,
        top: ($(window).width() - $('#modal').width()) / 7,
        position:'absolute'
    });
}

function getQsn(type_id){
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest();}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState===4 && xmlhttp.status===200) {
			document.getElementById('obj-qsn-box').innerHTML = xmlhttp.responseText;
	    }
    };
    xmlhttp.open("GET","execute.php?ajax=getQsn&type_id="+type_id,true);
    xmlhttp.send();		   
}

function addStmt(MaxStmt){
   StmtNo++;
   if(MaxStmt != 8){
	   if(StmtNo <= 4){
		   document.getElementById('Stmt'+StmtNo).innerHTML = "<td><span class='add-on'>"+StmtNo+".</span><textarea name='stmt"+StmtNo+"' class='miniStmt' required placeholder=''></textarea></td>";
	   } else {
	   alert('Maximum statements: 4 Only');
	   }
   } else {
	   if(StmtNo <= 8){
		   document.getElementById('Stmt'+StmtNo).innerHTML = "<td><span class='add-on'>"+StmtNo+".</span><textarea name='stmt"+StmtNo+"' class='miniStmt' required placeholder=''></textarea></td>";
	   } else {
		   alert('Maximum statements: 8 Only');
	   }
   } 
}

function addRow(){
   RowNo++;
   if(RowNo <= 4){
	   if(RowNo == 2){ var ColB = 'ii'; } else if(RowNo == 3) { var ColB = 'iii'; } else if (RowNo == 4) { var ColB = 'iv'; }
   document.getElementById('Row'+RowNo).innerHTML = "<td><span class='add-on'>"+RowNo+".</span><textarea name='colA"+RowNo+"' class='miniStmt' required placeholder=''></textarea></td><td><span class='add-on'>"+ColB+".</span><textarea name='colB"+RowNo+"' class='miniStmt' required placeholder=''></textarea></td>"
   } else {
	   alert('Maximum rows: 4 Only');
   }
}

   // Ajax to show Units Auto
function showUnits(sub_id) {
    if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest();}
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        	document.getElementById('units').innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","execute.php?ajax=showUnits&sub_id="+sub_id,true);
    xmlhttp.send();
}

function chapTotal(chapter, marks, qsns){
	var e = document.getElementById('chap'+chapter+'e'+marks).value;
	var m = document.getElementById('chap'+chapter+'m'+marks).value;
	var h = document.getElementById('chap'+chapter+'h'+marks).value;
	
	var total = (parseInt(e) + parseInt(m) + parseInt(h));
	if(total>=0){
		document.getElementById('chap'+chapter+'T'+marks).innerHTML = total;
	}
	
	var qsnTotal = 0;
	while(qsns>0){
		qsnTotal = qsnTotal + parseInt(document.getElementById('chap'+qsns+'T'+marks).innerHTML);
		qsns--;
	}
	if(qsnTotal > parseInt(document.getElementById('total'+marks).innerHTML))
		document.getElementById('qsnsT'+marks).innerHTML = qsnTotal+'<br><span style="color:red">Invalid</span>';
	else
		document.getElementById('qsnsT'+marks).innerHTML = qsnTotal;
}

function changeTotal(examType){
	if(examType == 'mid-term'){
		document.getElementById('total1').innerHTML = 20;
		document.getElementById('total2').innerHTML = 15;
	} else if (examType == 'pre-final'){
		document.getElementById('total1').innerHTML = 50;
		document.getElementById('total2').innerHTML = 25;
	}
}

function putChapters(sub_id){
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest();}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState===4 && xmlhttp.status===200) {
			document.getElementById('exam-chapters').innerHTML = xmlhttp.responseText;
	    }
    };
    xmlhttp.open("GET","execute.php?ajax=showUnits&sub_id="+sub_id+"&exampaper=",true);
    xmlhttp.send();	
}