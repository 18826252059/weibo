<?php 
//require_once '../include.php';
require_once "../core/admin.inc.php";
require_once "../lib/commom.func.php";
session_start();
checkLogined();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>后台管理</title>
<link rel="stylesheet" href="styles/backstage.css">
    <link href="scripts/css/jquery-accordion-menu.css" rel="stylesheet" type="text/css" />
    <link href="scripts/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <script src="scripts/js/jquery-1.11.2.min.js" type="text/javascript"></script>
   <!-- <script src="scripts/js/jquery-accordion-menu.js" type="text/javascript"></script>
    <script type="text/javascript">

        $(function(){
            //顶部导航切换
            $("#demo-list dd").click(function(){
                $("#demo-list dd.active").removeClass("active")
                $(this).addClass("active");
            })
        })
    </script>-->
</head>

<body>
    <div class="head">

            <h3 class="head_text fr">个人微博后台管理系统</h3>
    </div>
    <div class="operation_user clearfix">
       <!--   <div class="link fl"><a href="#">慕课</a><span>&gt;&gt;</span><a href="#">商品管理</a><span>&gt;&gt;</span>商品修改</div>-->
        <div class="link fl">
            <b>欢迎您,管理员
            <?php 
				if(isset($_SESSION['adminName'])){
					echo $_SESSION['adminName'];
				}elseif(isset($_COOKIE['adminName'])){
					echo $_COOKIE['adminName'];
				}
            ?>
            
            <!--</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="icon icon_i">首页</a><span></span><a href="#" class="icon icon_j">前进</a><span></span><a href="#" class="icon icon_t">后退</a><span></span><a href="#" class="icon icon_n">刷新</a>--><span></span><a href="doAdminAction.php?act=logout" class="icon icon_e">退出</a>
        </div>
    </div>
    <div class="content clearfix">
        <div class="main">
            <!--右侧内容-->
            <div class="cont">
                <div class="title">后台管理</div>
      	 		<!-- 嵌套网页开始 -->         
                <iframe src="main.php"  frameborder="0" name="mainFrame" width="100%" height="522"></iframe>
                <!-- 嵌套网页结束 -->   
            </div>
        </div>
        <!--左侧列表-->
        <div class="menu">
            <div class="cont">
                <div class="title">管理员</div>
               <ul class="mList">
                    <li>
                        <h3><span onclick="show('menu1','change1')" id="change1">+</span>微博管理</h3>
                        <dl id="menu1" style="display:none;">
                        	<dd><a href="addBlog.php" target="mainFrame">添加微博</a></dd>
                            <dd><a href="listBlog.php" target="mainFrame">微博列表</a></dd>
                        </dl>
                    </li>
                    <li>
                        <h3><span onclick="show('menu4','change4')" id="change4">+</span>用户管理</h3>
                        <dl id="menu4" style="display:none;">
                        	<dd><a href="addUser.php" target="mainFrame">添加用户</a></dd>
                            <dd><a href="listUser.php" target="mainFrame">用户列表</a></dd>
                        </dl>
                    </li>
                    <li>
                        <h3><span onclick="show('menu5','change5')" id="change5">+</span>管理员管理</h3>
                        <dl id="menu5" style="display:none;">
                        	<dd><a href="addAdmin.php" target="mainFrame">添加管理员</a></dd>
                            <dd><a href="listAdmin.php" target="mainFrame">管理员列表</a></dd>
                        </dl>
                    </li>

                  <!-- <li>
                        <h3><span onclick="show('menu2','change2')" id="change2">+</span>图片管理</h3>
                        <dl id="menu2" style="display:none;">
                            <dd><a href="listProImages.php" target="mainFrame">图片列表</a></dd>
                        </dl>
                    </li>-->
                   <li>
                       <h3><span onclick="show('menu3','change3')" id="change3">+</span>评论管理</h3>
                       <dl id="menu3" style="display:none;">
                           <dd><a href="listDis.php" target="mainFrame">评论列表</a></dd>
                       </dl>
                   </li>
                </ul>

            </div>

        </div>

    </div>
    <script type="text/javascript">
    	function show(num,change){
	    		var menu=document.getElementById(num);
	    		var change=document.getElementById(change);
	    		if(change.innerHTML=="+"){
	    				change.innerHTML="-";
	        	}else{
						change.innerHTML="+";
	            }
    		   if(menu.style.display=='none'){
    	             menu.style.display='';
    		    }else{
    		         menu.style.display='none';
    		    }
        }
    </script>
    <script type="text/javascript">
        (function($) {
            $.expr[":"].Contains = function(a, i, m) {
                return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
            };
            function filterList(header, list) {
                //@header 头部元素
                //@list 无需列表
                //创建一个搜素表单
                var form = $("<form>").attr({
                    "class":"filterform",
                    action:"#"
                }), input = $("<input>").attr({
                    "class":"filterinput",
                    type:"text"
                });
                $(form).append(input).appendTo(header);
                $(input).change(function() {
                    var filter = $(this).val();
                    if (filter) {
                        $matches = $(list).find("a:Contains(" + filter + ")").parent();
                        $("dd", list).not($matches).slideUp();
                        $matches.slideDown();
                    } else {
                        $(list).find("dd").slideDown();
                    }
                    return false;
                }).keyup(function() {
                    $(this).change();
                });
            }
            $(function() {
                filterList($("#form"), $("#demo-list"));
            });
        })(jQuery);
    </script>

    <script type="text/javascript">

        jQuery("#jquery-accordion-menu").jqueryAccordionMenu();

    </script>
</body>
</html>