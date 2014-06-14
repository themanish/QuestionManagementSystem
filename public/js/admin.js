/* Teachers */

function showSems(fac_id)
{
    if (window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        document.getElementById('semester').innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","execute.php?ajax=&fac_id="+fac_id,true);
    xmlhttp.send();
}
function showSubs(sem)
{
    var e = document.getElementById('faculty');
    var fac_id = e.options[e.selectedIndex].value;
    if (window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        document.getElementById('subjects').innerHTML = xmlhttp.responseText;
        //alert(xmlhttp.responseText);
        }
    };
    xmlhttp.open("GET","execute.php?ajax=&fac_id="+fac_id+"&semester="+sem,true);
    xmlhttp.send();
}

/* fs_subnchap */

function showSems(str)
{
    if (str==="") {
        // Not selected state
    }
    if (window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        document.getElementById('semester').innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","execute.php?ajax=&fac_id="+str,true);
    xmlhttp.send();
}
function editChap(chap_id, sub_id){
    if (window.XMLHttpRequest) {
      xmlhttp=new XMLHttpRequest();
    }
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
        document.getElementById('add-edit-chapters').innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","execute.php?chap_ajax_edit=&chap_id="+chap_id+"&sub_id="+sub_id,true);
    xmlhttp.send();
}

/* admin dash header */

// Display the popup in center
function positionPopup(){
    if(!$("#modal").is(':visible')){ return; }
    $("#modal").css({
        left: ($(window).width() - $('#modal').width()) / 2,
        top: ($(window).width() - $('#modal').width()) / 7,
        position:'absolute'
    });
}
