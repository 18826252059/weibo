

var operateType = "";

var courses = courses || {};

var selectcourses = selectcourses || {};

var searchSelectCourses = searchSelectCourses || {};

var SelectCourse = {
    Create:function(cid,cname,teacherName,grade,time,addr){
        this.cid = cid;
        this.cname = cname;
        this.teacherName = teacherName;
        this.grade = grade;
        this.time = time;
        this.addr = addr;
    },
    
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
        searchSelectCourses = new Array();
        var flag = false;
        for(var i in selectcourses){
            if(data.cid.indexOf(selectcourses[i].cid) >= 0 || 
                    data.cname.indexOf(selectcourses[i].cname) >= 0){
                flag = true;
                searchSelectCourses[selectcourses[i].cid] = selectcourses[i];
                refreshDatas(searchSelectCourses);
            } 
        }
        if (!flag) {
            alert("没有相关记录!");
        };
    }
};

var Course = {
    Create:function(cid,cname,teacherName,grade,time,addr){
        this.cid = cid;
        this.cname = cname;
        this.teacherName = teacherName;
        this.grade = grade;
        this.time = time;
        this.addr = addr;
    },
    //查找课程
    findCourseData:function(data){
        if (operateType == "find") {
        searchCourses = new Array();
        var flag = false;
        for(var i in courses){
            if(data.cname.indexOf(courses[i].cname) >= 0 || 
                    data.teacherName.indexOf(courses[i].teacherName) >= 0){
                flag = true;
                searchCourses[courses[i].cid] = courses[i];
                addRowData(searchCourses);
            } 
        }
        if (!flag) {
            alert("没有相关记录!");
        };
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
function loadSelelctDatas(){   

    var selectcourses = {};
    $.post('loadSelect',
            null,//如果你需要传参数的话，可以写在这里，格式为：{参数名：值,参数名：值...}                  
            function(data){//执行成功之后的回调函数           
                // alert(data); 
                var models = eval('('+data+')');// 对Servlet回传的字符串转换为json对象数组
                var cs = models[0];
                for(k in cs){
                    var u = cs[k];
                    var initCourse = New(SelectCourse,[u.cid,u.cname,u.teacherName,u.grade,u.time,u.addr]);
                    selectcourses[k] = initCourse;
                };
                addRowSelectData(selectcourses);             
            }
    ); 
    return selectcourses;
};

function loadCourseDatas(){   
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
                }
                addRowData(courses);
                             
            }
    );
    return courses;
}

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

function addRowSelectData (datas){
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
        html = html +  "<tr class='"+ color +"'><td style='width:50px;'><input type='checkbox' checked='checked'></td><td id='cid'>"
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

function collectionRowData(param){
    var tdElement = param.getElementsByTagName("td");
    var courseArr = [];
    for(var i=1;i<tdElement.length;i++){
        var temp =  tdElement[i].textContent;
        courseArr[i-1] = temp;
    }
    var course = New(Course,courseArr);
    return course;
}

function optionUser(param){
    //获得操作类别
    var optionType = param.getAttribute("id");
    if(optionType == "user_add"){
        operateType = "add";
        courses = loadCourseDatas();
    }else if(optionType == "user_delete"){
        operateType = "delete";
        loadSelelctDatas();    
    }else if(optionType == "user_check"){
        operateType = "check";
        loadSelelctDatas();
    }else if(optionType == "user_find"){
        operateType = "find";
        var s_cname =  document.getElementById("s_cname").value;
        var s_teacherName =  document.getElementById("s_teacherName").value;
        
        //搜索数据
        var s_data = s_data || {};
        s_data.cname = s_cname;
        s_data.teacherName = s_teacherName;
        
        // alert(s_data.code+"jjjj"+s_data.name);
        var course = New(Course,[]);
        course.findCourseData(s_data);
    }else{

    }
}

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
        return -1;
    }
}