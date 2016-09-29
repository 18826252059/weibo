/*
*	评论表情渲染JS
*	@author:	小毛
*	@data:		2013年2月17日
*	@version:	1.0
*	@rely:		jQuery
*/
$(function(){
	/*
	*		参数说明
	*		baseUrl:	【字符串】表情路径的基地址
	*		pace:		【数字】表情弹出层淡入淡出的速度
	*		dir:		【数组】保存表情包文件夹名字
	*		text:		【二维数组】保存表情包title文字
	*		num:		【数组】保存表情包表情个数
	*		isExist:	【数组】保存表情是否加载过,对于加载过的表情包不重复请求。
	*/
	var rl_exp = {
		baseUrl:	'',
		pace:		200,
		dir:		['mr','gnl','lxh','bzmh'],
		text:[			/*表情包title文字，自己补充*/
			[
				'mr_0','mr_1','mr_2','mr_3','mr_4','mr_5','mr_6','mr_7','mr_8','mr_9','mr_10','mr_11','mr_12','mr_13','mr_14','mr_15','mr_16','mr_17','mr_18','mr_19',
				'mr_20','mr_21','mr_22','mr_23','mr_24','mr_25','mr_26','mr_27','mr_28','mr_29','mr_30','mr_31','mr_32','mr_33','mr_34','mr_35','mr_36','mr_37','mr_38','mr_39',
				'mr_40','mr_41','mr_42','mr_43','mr_44','mr_45','mr_46','mr_47','mr_48','mr_49','mr_50','mr_51','mr_52','mr_53','mr_54','mr_55','mr_56','mr_57','mr_58','mr_59',
				'mr_60','mr_61','mr_62','mr_63','mr_64','mr_65','mr_66','mr_67','mr_68','mr_69','mr_70','mr_71','mr_72','mr_73','mr_74','mr_75','mr_76','mr_77','mr_78','mr_79',
				'mr_80','mr_81','mr_82','mr_83','mr_84','mr_85','mr_86','mr_87','mr_88','mr_89','mr_90','mr_91','mr_92','mr_93','mr_94','mr_95'
			],
			[
				'gnl_0','gnl_1','gnl_2','gnl_3','gnl_4','gnl_5','gnl_6','gnl_7','gnl_8','gnl_9','gnl_10','gnl_11','gnl_12','gnl_13','gnl_14','gnl_15','gnl_16','gnl_17','gnl_18','gnl_19',
				'gnl_20','gnl_21','gnl_22','gnl_23','gnl_24','gnl_25','gnl_26','gnl_27','gnl_28','gnl_29','gnl_30','gnl_31','gnl_32','gnl_33','gnl_34','gnl_35','gnl_36','gnl_37','gnl_38','gnl_39',
				'gnl_40','gnl_41','gnl_42','gnl_43','gnl_44','gnl_45','gnl_46','gnl_47','gnl_48','gnl_49','gnl_50','gnl_51','gnl_52','gnl_53','gnl_54','gnl_55','gnl_56','gnl_57','gnl_58','gnl_59',
				'gnl_60','gnl_61','gnl_62','gnl_63','gnl_64','gnl_65','gnl_66','gnl_67','gnl_68','gnl_69','gnl_70','gnl_71','gnl_72','gnl_73','gnl_74','gnl_75','gnl_76','gnl_77','gnl_78','gnl_79',
				'gnl_80','gnl_81','gnl_82','gnl_83','gnl_84','gnl_85','gnl_86','gnl_87','gnl_88','gnl_89','gnl_90','gnl_91','gnl_92','gnl_93','gnl_94','gnl_95'
			],
			[
				'lxh_0','lxh_1','lxh_2','lxh_3','lxh_4','lxh_5','lxh_6','lxh_7','lxh_8','lxh_9','lxh_10','lxh_11','lxh_12','lxh_13','lxh_14','lxh_15','lxh_16','lxh_17','lxh_18','lxh_19',
				'lxh_20','lxh_21','lxh_22','lxh_23','lxh_24','lxh_25','lxh_26','lxh_27','lxh_28','lxh_29','lxh_30','lxh_31','lxh_32','lxh_33','lxh_34','lxh_35','lxh_36','lxh_37','lxh_38','lxh_39',
				'lxh_40','lxh_41','lxh_42','lxh_43','lxh_44','lxh_45','lxh_46','lxh_47','lxh_48','lxh_49','lxh_50','lxh_51','lxh_52','lxh_53','lxh_54','lxh_55','lxh_56','lxh_57','lxh_58','lxh_59',
				'lxh_60','lxh_61','lxh_62','lxh_63','lxh_64','lxh_65','lxh_66','lxh_67','lxh_68','lxh_69','lxh_70','lxh_71','lxh_72','lxh_73','lxh_74','lxh_75','lxh_76','lxh_77','lxh_78','lxh_79',
				'lxh_80','lxh_81','lxh_82','lxh_83','lxh_84','lxh_85','lxh_86','lxh_87','lxh_88','lxh_89','lxh_90','lxh_91','lxh_92','lxh_93','lxh_94','lxh_95'
			],
			[
				'bzmh_0','bzmh_1','bzmh_2','bzmh_3','bzmh_4','bzmh_5','bzmh_6','bzmh_7','bzmh_8','bzmh_9','bzmh_10','bzmh_11','bzmh_12','bzmh_13','bzmh_14','bzmh_15','bzmh_16','bzmh_17','bzmh_18','bzmh_19',
				'bzmh_20','bzmh_21','bzmh_22','bzmh_23','bzmh_24','bzmh_25','bzmh_26','bzmh_27','bzmh_28','bzmh_29','bzmh_30','bzmh_31','bzmh_32','bzmh_33','bzmh_34','bzmh_35','bzmh_36','bzmh_37','bzmh_38','bzmh_39',
				'bzmh_40','bzmh_41','bzmh_42','bzmh_43','bzmh_44','bzmh_45','bzmh_46','bzmh_47','bzmh_48','bzmh_49','bzmh_50','bzmh_51','bzmh_52','bzmh_53','bzmh_54','bzmh_55','bzmh_56','bzmh_57','bzmh_58','bzmh_59',
				'bzmh_60','bzmh_61','bzmh_62','bzmh_63','bzmh_64','bzmh_65','bzmh_66','bzmh_67','bzmh_68','bzmh_69','bzmh_70','bzmh_71','bzmh_72','bzmh_73','bzmh_74','bzmh_75','bzmh_76','bzmh_77','bzmh_78','bzmh_79',
				'bzmh_80','bzmh_81','bzmh_82','bzmh_83','bzmh_84','bzmh_85','bzmh_86','bzmh_87','bzmh_88','bzmh_89','bzmh_90','bzmh_91','bzmh_92','bzmh_93','bzmh_94','bzmh_95'
			],
		],
		num:		[84,46,82,69],
		isExist:	[0,0,0,0],
		bind:	function(i){
			$("#rl_bq .rl_exp_main").eq(i).find('.rl_exp_item').each(function(){
				$(this).bind('click',function(){
					rl_exp.insertText(document.getElementById('rl_exp_input'),'['+$(this).find('img').attr('title')+']');
					$('#rl_bq').hide();
				});
			});
		},
		/*加载表情包函数*/
		loadImg:function(i){
			var node = $("#rl_bq .rl_exp_main").eq(i);
			for(var j = 0; j<rl_exp.num[i];j++){
				var domStr = 	'<li class="rl_exp_item">' + 
									'<img src="' + rl_exp.baseUrl + 'img/' + rl_exp.dir[i] + '/' + j + '.gif" alt="' + rl_exp.text[i][j] + 
									'" title="' + rl_exp.text[i][j] + '" />' +
								'</li>';
				$(domStr).appendTo(node);
			}
			rl_exp.isExist[i] = 1;
			rl_exp.bind(i);
		},
		/*在textarea里光标后面插入文字*/
		insertText:function(obj,str){
			obj.focus();
			if (document.selection) {
				var sel = document.selection.createRange();
				sel.text = str;
			} else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
				var startPos = obj.selectionStart,
					endPos = obj.selectionEnd,
					cursorPos = startPos,
					tmpStr = obj.value;
				obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
				cursorPos += str.length;
				obj.selectionStart = obj.selectionEnd = cursorPos;
			} else {
				obj.value += str;
			}
		},
		init:function(){
			$("#rl_bq > ul.rl_exp_tab > li > a").each(function(i){
				$(this).bind('click',function(){
					if( $(this).hasClass('selected') )
						return;
					if( rl_exp.isExist[i] == 0 ){
						rl_exp.loadImg(i);
					}
					$("#rl_bq > ul.rl_exp_tab > li > a.selected").removeClass('selected');
					$(this).addClass('selected');
					$('#rl_bq .rl_selected').removeClass('rl_selected').hide();
					$('#rl_bq .rl_exp_main').eq(i).addClass('rl_selected').show();
				});
			});
			/*绑定表情弹出按钮响应，初始化弹出默认表情。*/
			$("#rl_exp_btn").bind('click',function(){
				if( rl_exp.isExist[0] == 0 ){
					rl_exp.loadImg(0);
				}
				var w = $(this).position();
				$('#rl_bq').css({left:w.left,top:w.top+30}).show();
			});
			/*绑定关闭按钮*/
			$('#rl_bq a.close').bind('click',function(){
				$('#rl_bq').hide();
			});
			/*绑定document点击事件，对target不在rl_bq弹出框上时执行rl_bq淡出，并阻止target在弹出按钮的响应。*/
			$(document).bind('click',function(e){
				var target = $(e.target);
				if( target.closest("#rl_exp_btn").length == 1 )
					return;
				if( target.closest("#rl_bq").length == 0 ){
					$('#rl_bq').hide();
				}
			});
		}
	};
	rl_exp.init();	//调用初始化函数。
});