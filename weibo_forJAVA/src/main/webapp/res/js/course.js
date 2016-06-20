/**
 * 
 */
//存放所有用户
// var users = users || {};
// var users = loadUserDatas();
//操作类型
var operateType = "";
//存放搜索对象
// var searchUsers = searchUsers || {};

var searchCourses = searchCourses || {};
var courses = loadCourseDatas();
//课程构造方法
var Course = {
    Create:function(cid,cname,teacherName,grade,time,addr){
        this.cid = cid;
        this.cname = cname;
        this.teacherName = teacherName;
        this.grade = grade;
        this.time = time;
        this.addr = addr;
    },
    // //添加课程
    // addCourseData:function(){
    //     if (this.cid != "") {
    //         courses[this.cid] = this;
    //     };
    // },
    //删除课程
    deleteCourseData:function(){
        var data = {cid:this.cid};
        $.ajax({
            url:"delCourse",
            type:"POST",
            dataType:"json",
            data:data,
            success:function(data){
                data=eval(data);
                alert(data.msg);
                loadCourseDatas();
            }
        });
    },
    //查找课程
    findCourseData:function(data){
        searchCourses = new Array();
        var flag = false;
        for(var i in courses){
            if(data.cid.indexOf(courses[i].cid) >= 0 || 
                    data.cname.indexOf(courses[i].cname) >= 0){
                flag = true;
                searchCourses[courses[i].cid] = courses[i];
                refreshDatas(searchCourses);
            } 
        }
        if (!flag) {
            alert("没有相关记录!");
        };
    }
};


function New(aClass,aParams){
    function new_(){
        aClass.Create.apply(this,aParams);
    }
    new_.prototype = aClass;
    return new new_();
}



/**
 * 首次加载页面执行方法
 */
function loadCourseDatas(){   
//    var initUser1 = New(User,["1001","小兰","女","1234","13","1991-1-1"]);
//    var initUser2 = New(User,["1002","小毅","男","1234","13","1991-1-1"]);
//    var initUser3 = New(User,["1003","兰花","女","1234","13","1991-1-1"]);
//    var initUser4 = New(User,["1004","兰儿","女","1234","13","1991-1-1"]);
//    users[initUser1.code] = initUser1;
//    users[initUser2.code] = initUser2;
//    users[initUser3.code] = initUser3;
//    users[initUser4.code] = initUser4;
//    return users;
	var courses = {};
	$.post('loadCourse',
			null,//如果你需要传参数的话，可以写在这里，格式为：{参数名：值,参数名：值...}                  
			function(data){//执行成功之后的回调函数           
				// alert(data); 
				var models = eval('('+data+')');// 对Servlet回传的字符串转换为json对象数组
				var cs = models[0];
				for(k in cs){
					var u = cs[k];
					var initCourse = New(Course,[u.cid,u.cname,u.teacherName,u.grade,u.time,u.addr]);
				    courses[k] = initCourse;
                    // alert(courses[k]);
				}
                // alert(courses);
				//$('#myModal').modal('hide');
				addRowData(courses);
				// refreshDatas(courses);              
			}
	);
	
	
	
//    var initUser4 = New(User,["1004","兰儿","女","1234","13","1991-1-1"]);
//    users[initUser1.code] = initUser1;
//    users[initUser2.code] = initUser2;
//    users[initUser3.code] = initUser3;
//    users[initUser4.code] = initUser4;
    return courses;
}

/**
 * 往表格添加一行html数据
 */
function addRowData (datas){
    var tbodyElement = document.getElementById("tbody");
    var html = "";
    var color = "warning";
    var flag = true;
    for(var i in datas){
        if(flag){
            color = "info";
        }else{
            color = "warning";
        }
        html = html +  "<tr class='"+ color +"'><td style='width:50px;'><input type='checkbox'></td><td id='cid'>"
                + datas[i].cid +"</td><td id='cname'>"
                + datas[i].cname +"</td><td id='teacherName'>"
                + datas[i].teacherName +"</td><td id='grade'>"
                + datas[i].grade +"</td><td id='time'>"
                + datas[i].time +"</td><td id='addr'>"
                + datas[i].addr +"</td>" 
                +"</tr>";
                
        flag = !flag;//颜色转换
    }
    tbodyElement.innerHTML = html;
}
/**
 * 刷新用户数据
 */
function refreshDatas(datas){
    addRowData(datas);
};

/**
 * 收集一行数据
 */
function collectionRowData(param){
    var tdElement = param.getElementsByTagName("td");
    var courseArr = [];
    for(var i=1;i<tdElement.length;i++){
        var temp =  tdElement[i].textContent;
        courseArr[i-1] = temp;
    }
    var course = New(Course,courseArr);
   // alert(user);
    return course;
}
/**
 * 用户操作方法
 */
 function optionCourseData(param){
    //获得操作类别
    var optionType = param.getAttribute("id");
    if(optionType == "user_add"){
        operateType = "add";
        var modal_body = document.getElementById("modal-body");
        var inputElements=  modal_body.getElementsByTagName("input");
        for(var i=0;i<inputElements.length;i++){
            inputElements[i].value = "";
        };
    }else if(optionType == "user_delete"){
        var checkRowData = isCheckedData();
        if (checkRowData != -1) {
            var course = collectionRowData(checkRowData);
            course.deleteCourseData(); 
        };       
    }else if(optionType == "user_edit"){
        var checkRowData = isCheckedData();
        if (checkRowData != -1) {
            operateType = "edit";
            var user = collectionRowData(checkRowData);
            var modal_body = document.getElementById("modal-body");
            var inputElements=  modal_body.getElementsByTagName("input");
            for(var i=0;i<inputElements.length;i++){
                var temp = inputElements[i].id.substring(2,inputElements[i].id.length)
                inputElements[i].value = user[temp];
            };
        } else {
            $('#myModal').modal();
        }
    }else if(optionType == "user_find"){
        var s_cid =  document.getElementById("s_cid").value;
        var s_cname =  document.getElementById("s_cname").value;
        
        //搜索数据
        var s_data = s_data || {};
        s_data.cid = s_cid;
        s_data.cname = s_cname;
        
        // alert(s_data.code+"jjjj"+s_data.name);
        var course = New(Course,[]);
        course.findCourseData(s_data);
    }else{

    }
}

/**
 * 是否选中数据,返回选中数据的行
 */
function isCheckedData(){
    var tbodyElement =document.getElementById("tbody");
    var trElements = tbodyElement.getElementsByTagName("tr");
    var flag = false;
    for(var i=0;i<trElements.length;i++){
        var inputElement = trElements[i].getElementsByTagName("input")[0];
        if(inputElement.checked){
            flag = true;
            return trElements[i];
        }
    }
    if(!flag){
        alert("请选择一条记录！");
        // unbind之后要记得重新bind
        $('#myModal').unbind("on");
        return -1;
    }
}
