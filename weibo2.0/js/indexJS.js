/**
 * Created by star on 2016/5/11.
 */
function showInstall(){
    var dt=document.getElementsByTagName("dt");

    for(var i=0;i<dt.length;i++){
        dt[i].style.display="block";
        //dt[i].style.visibility="visible";
    }
}

function hiddenInstall(){
    var dt=document.getElementsByTagName("dt");
    for(var i=0;i<dt.length;i++){
        dt[i].style.display="none";
       // dt[i].style.visibility="hidden";

    }
}

function searchbyEnter(){
    if ( event.keyCode == 13){
        search();
    }
}

function search(){
    var val=document.getElementById("search").value;
    if(val.trim()!=""){
        window.location="search.php?keywords="+val;
    }


}

function checkTextIsNull(){
    if($('#show').val().trim()==""){
        alert("发布内容不能为空");
        return false;
    }else{
        $('.spinner').css("display","block");
        $('#bg').css("display","block");
    }

    return true;
}



