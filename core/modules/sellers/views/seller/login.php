<?php



if($code==1){

    echo "<script>alert('用户名或者密码不正确！')</script>";
}


?>
<html>
<style>
    @media screen and (max-width:767px){.login_wrap .panel.panel-default{width:90%; min-width:300px;}}
    @media screen and (min-width:768px){.login_wrap .panel.panel-default{width:70%;}}
    @media screen and (min-width:1200px){.login_wrap .panel.panel-default{width:50%;}}
    body {
   background:url(<?= Yii::$app->request->baseUrl ?>/statics/images/seller_login_bg.jpg) no-repeat;
   background-size: cover;
    }

    body{
        background-color: white;
    }

    /*———————————————第一个卡通样式———————————————*/
    .login_wrap{
        width: 518px;
        margin-left: 350px;
    }
    .sellback{
        width: 900px;
        height: 440px;
        background-color: white;
        margin: 250px auto 0px;
        background:url(<?= Yii::$app->request->baseUrl ?>/statics/images/container_bg0.png) no-repeat;
    }
    .warp_content{ width:270px; margin:0 auto; padding-top:20px;}
    .warp_content .title{ margin-top:38px; padding-left:20px;}
    .warp_content .title h3{ font-size:30px; color:#7f6f67; line-height:32px;}
    .warp_content .title span{

        height:12px;
        display:block;
        margin-top:5px;
    }
    .formInfo{ margin-top:20px; overflow:hidden;}
    .formInfo .formText{ margin-bottom:12px; position:relative; z-index:2;}
    .formInfo .formText .input-text{
        border:0; height:40px;
        padding:6px 28px 6px 42px;
        width:270px;
        border-radius:20px;
        background-color: #f4f4f4;
    }
    .formInfo .formText input:focus{ outline:none;box-shadow:none;}
    .formInfo .formText .checked{ background-position:-109px -12px;}
    .formInfo .formText .login-icon{

        position: absolute;
        top: 13px;
        left: 17px;
        width: 14px;
        height: 13px;
        z-index: 9;
    }
    .formInfo .formText .login-icon-user{ background-position:-5px -8px;}
    .formInfo .formText .login-icon-pwd{ background-position:-3px -38px;}
    .formInfo .focus .login-icon-user{ background-position:-33px -8px;}
    .formInfo .focus .login-icon-pwd{ background-position:-31px -38px;}
    .formInfo .submitDiv{ font-size:0; padding-top:20px;}
    .formInfo .submitDiv .input-yzm{ border:0; height:28px; padding:6px 5px 6px 20px; background:#f4f4f4; border-radius:50px 0 0 50px; width:110px;}
    .formInfo .submitDiv .sub{ width: 135px;height: 40px;border: 0;background: #ff7c3a;color: #FFF;border-radius: 0 50px 50px 0;padding: 0;font-size: 16px;cursor: pointer;font-family: "Microsoft YaHei";}
    .formInfo .submitDiv .qp_sub{ width:100%; border-radius:50px;}

    /*———————————————第二个样式—————————————————*/

    .msecbox{width: 420px;margin: 0px auto;}
    .msecheader{width: 420px;height: 147px;text-align: center;margin-bottom: 20px;}
    .msecheader>img{width: 135px;height: 147px;}
    .mscontent{background-color: white;border-radius: 10px;margin: 0px auto;padding: 1px;width: 420px;height: 400px;}
    .msfutitle,.mstitle{text-align: center;font-family: "微软雅黑";color: #1ABC9C;margin-bottom: 0px;}
    .mstitle{font-size: 28px;margin-top: 35px;font-weight: bold;}
    .msfutitle{font-size: 20px;}
    .msbanner{border:1px solid #C0C0C0;padding: 5px;width: 300px;margin: 35px auto 20px;}
    .msbanner2>span,.msbanner>span{color: #CCCCCC;font-size: 26px;margin-left: 10px;}
    .msbanner2>input,.msbanner>input{width: 215px;height: 40px;margin-left: 10px;outline: none;border:none;}
    .msbanner2{border:1px solid #C0C0C0;padding: 5px;width: 300px;margin: 20px auto;}
    .msbanner3{margin: 0px auto;width: 300px;height: 50px;}
    .msbanner3>input{background-color: #1ABC9C;width: 300px;height: 50px;border:none;border-radius: 4px;color: white;font-size: 18px;}
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color: #CCCCCC;
    }

    ::-moz-placeholder { /* Mozilla Firefox 19+ */
        color: #CCCCCC;
    }

    input:-ms-input-placeholder{
        color: #CCCCCC;
    }

    input::-webkit-input-placeholder{
        color: #CCCCCC;
    }
</style>

<body>



<!-- —————————————第二套——————————— -->
<div class="sellback">
    <div class="login_wrap">
        <div class="warp_content">
            <div class="title">
                <h3>天天出海商家中心</h3>
                <span class="txt"></span>
            </div>
            <form action="" method="post" role="form" id="form1" onsubmit="return formcheck();">
                <div class="formInfo">
                    <div class="formText">
                        <i class="login-icon login-icon-user"></i>
                        <input type="text" name="username" autocomplete="off" class="input-text" value="" placeholder="用户名">
                    </div>
                    <div class="formText">
                        <i class="login-icon login-icon-pwd"></i>
                        <input type="password"   style="display:none"/>
                        <input type="password" name="password" autocomplete="off" class="input-text" value="" placeholder="密  码">
                    </div>
                    <div class="formText submitDiv">
                        <span class="submit_span">
                        	<input type="submit" id="submit" name="submit" value="登录" class="sub qp_sub" value="登 录" />
                        </span>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<script>
    function formcheck() {
        if($('#remember:checked').length == 1) {
            cookie.set('remember-username', $(':text[name="username"]').val());
        } else {
            cookie.del('remember-username');
        }
        return true;
    }
    $('#toggle').click(function() {
        $('#imgverify').prop('src', '{php echo $_W['siteroot'].url("utility/code")}r='+Math.round(new Date().getTime()));
        return false;
    });

</script>
</body>
</html>
