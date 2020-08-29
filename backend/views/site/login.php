<?php
use yii\helpers\Url;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>后台管理平台</title>
    <link href="/themes/css/login.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="login">
    <div id="login_header">
<!--        <h1 class="login_logo">-->
<!--            <a href="http://demo.dwzjs.com"><img src="/images/logo.png" width="240"> <!--img src="/themes/default/images/login_logo.gif" /></a>-->
<!--        </h1>-->
        <div class="login_headerContent">
            <div class="navList">
                <ul>
                    <li><a href="#">设为首页</a></li>
                    <li><a href="#">反馈</a></li>
                    <li><a href="#" target="_blank">帮助</a></li>
                </ul>
            </div>
            <h2 class="login_title">
                <img src="/themes/default/images/login_title_default.png" />
            </h2>
        </div>
    </div>
    <div id="login_content">
        <div class="loginForm">
            <form action="<?=Url::to(['site/login'])?>" method="post">
                <p>
                    <label>用户名：</label>
                    <input type="text" name="LoginForm[username]" size="20" value="<?=$model->username?>" class="login_input" />
                <p class="help-block help-block-error"></p>
                </p>
                <p>
                    <label>密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
                    <input type="password" name="LoginForm[password]" size="20" <?=$model->password?> class="login_input"/>
                </p>
<!--                <p>-->
<!--                    <label>验证码：</label>-->
<!--                    <input class="code" type="text" size="7" />-->
<!--                    <span><img src="/themes/default/images/header_bg.png" alt="" width="72" height="24" /></span>-->
<!--                </p>-->
                <p id="error" style="color:#f71752;margin-left:80px;">&nbsp;</p>
                <?php if($model->hasErrors()):?>
                    <script>
                        $('#error').text('用户名或者密码错误！');
                    </script>
                <?php endif;?>
                <div class="login_bar">
                    <input class="sub" type="submit" value=" " />
                </div>
            </form>
        </div>
        <div class="login_banner">
            <img src="/themes/default/images/login_banner_default.png" />
        </div>
        <div class="login_main">
            <ul class="helpList">
                <li><a href="#">网站首页入口</a></li>
                <li><a href="#">官网首页</a></li>
                <li><a href="#">忘记密码怎么办？</a></li>
                <li><a href="#">为什么登录失败？</a></li>
            </ul>
            <div class="login_inner">
                <p>请勿在非安全的网络中登陆后台，防止账号信息泄露</p>
                <p>有疑问联系xxxxx.mail.com</p>
                <p>最影后台管理平台，请您注意账号安全...</p>
            </div>
        </div>
    </div>
    <div id="login_footer">
        Copyright &copy; 2009 Kung Inc. All Rights Reserved.
    </div>
</div>
</body>
</html>
