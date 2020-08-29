<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kung后台管理平台</title>

<link href="/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="/themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->


<!--[if lt IE 9]>
<script src="/js/speedup.js" type="text/javascript"></script>
<script src="/js/jquery-1.11.3.min.js" type="text/javascript"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script src="/js/jquery-2.1.4.min.js" type="text/javascript"></script>
<!--<![endif]-->

<script src="/js/jquery.cookie.js" type="text/javascript"></script>
<script src="/js/jquery.validate.js" type="text/javascript"></script>
<script src="/js/jquery.bgiframe.js" type="text/javascript"></script>

<!-- 开启xheditor编辑器的api 现在已由 ueditor代替
<!--    <script src="/xheditor/xheditor-1.2.2.min.js" type="text/javascript"></script>-->
<!--    <script src="/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>-->

<!-- uploadfy文件上传控件
<script src="/uploadify/scripts/jquery.uploadify.js" type="text/javascript"></script>
-->

<!-- svg图表  supports Firefox 3.0+, Safari 3.0+, Chrome 5.0+, Opera 9.5+ and Internet Explorer 6.0+ -->

<!--
<script type="text/javascript" src="/chart/raphael.js"></script>
<script type="text/javascript" src="/chart/g.raphael.js"></script>
<script type="text/javascript" src="/chart/g.bar.js"></script>
<script type="text/javascript" src="/chart/g.line.js"></script>
<script type="text/javascript" src="/chart/g.pie.js"></script>
<script type="text/javascript" src="/chart/g.dot.js"></script>
-->

<!-- 开启百度地图的api
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=6PYkS1eDz5pMnyfO0jvBNE0F"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
-->

<!--<script src="/js/dwz.core.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.util.date.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.validate.method.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.barDrag.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.drag.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.tree.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.accordion.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.ui.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.theme.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.switchEnv.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.alertMsg.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.contextmenu.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.navTab.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.tab.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.resize.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.dialog.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.dialogDrag.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.sortDrag.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.cssTable.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.stable.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.taskBar.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.ajax.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.pagination.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.database.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.datepicker.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.effects.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.panel.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.checkbox.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.history.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.combox.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.file.js" type="text/javascript"></script>-->
<!--<script src="/js/dwz.print.js" type="text/javascript"></script>-->

<script src="/bin/dwz.min.js" type="text/javascript"></script>
<!-- 可以用dwz.min.js替换前面全部dwz.*.js (注意：替换时下面dwz.regional.zh.js还需要引入)
<script src="/bin/dwz.min.js" type="text/javascript"></script>
-->
<script src="/js/dwz.regional.zh.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        DWZ.init("dwz.frag.xml", {
            loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
            loginUrl:"login.html",	// 跳到登录页面
            statusCode:{ok:200, error:300, timeout:301}, //【可选】
            pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
            keys: {statusCode:"statusCode", message:"message"}, //【可选】
            ui:{hideMode:'offsets'}, //【可选】hideMode:navTab组件切换的隐藏方式，支持的值有’display’，’offsets’负数偏移位置的值，默认值为’display’
            debug:true,	// 调试模式 【true|false】
            callback:function(){
                initEnv();
                $("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
            }
        });
    });
</script>
