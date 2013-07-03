<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo Yii::app()->name;?> 后台管理</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <style type="text/css">
            /* Override some defaults */
        html, body {
            background-color: #eee;
        }

        body {
            /*padding-top: 40px;*/
        }

        .main-content {
            min-height: 410px;
        }

        .form {
            width: 300px;
            margin: auto;
            padding: 25px;
        }

            /* The white background content wrapper */
        .container > .content {
            background-color: #fff;
            padding: 20px;
            margin: 0 -20px;
            -webkit-border-radius: 10px 10px 10px 10px;
            -moz-border-radius: 10px 10px 10px 10px;
            border-radius: 10px 10px 10px 10px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
        }

        .login-form {
            margin-left: 65px;
        }

        legend {
            margin-right: -50px;
            font-weight: bold;
            color: #404040;
        }

        .footer {
            min-height: 75px;
            margin-bottom: 0px;
            border-top: 3px solid #783c89;
        }

        .header {
            min-height: 90px;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            if (window.self === window.top) {
                //alert('not in a frame');

            } else {
               // alert('in a frame');
              //  $(".hidden-if-in-IFrame").css("display","none")
              // 在iframe时 隐藏页眉页脚！
                $(".container").siblings().css("display","none");
            }
        });
    </script>
</head>
<body>
<?php $this->widget('ext.egradient.EGradient', array('target' => '.header,.footer', 'color1' => '#803c17', 'color2' => '#d27031'));?>

<!--header 部分-->
<div class="wrapper">
    <div class="header hidden-if-in-IFrame">
        <div class="container">
            <div class="row branding">
                <div class="span6">
                    <h1 class="pull-left">
                        <a href="<?php echo abu('index.php'); ?>" target="_blank">
                            Yii <strong>SNS</strong> application
                        </a>
                    </h1>
                </div>
                <div class="span6">
                    <p class="pull-right userInfo">
                        <i class="icon-user"></i>
                        欢迎登陆： <strong><?php echo Yii::app()->name; ?></strong> &nbsp;
                    </p>
                </div>
            </div>
            <!--            <div class="row navigation">-->
            <!--                <div class="span12">-->
            <!--                    <ul class="nav nav-tabs">-->
            <!--                        <li class="active"><a href="index.html">Home</a></li>-->
            <!--                        <li><a href="tour.html">Tour</a></li>-->
            <!--                        <li><a href="plans_pricing.html">Plans &amp; Pricing</a></li>-->
            <!--                        <li><a href="blog.html">Blog</a></li>-->
            <!--                    </ul>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
    </div>
    <!--header结束-->
    <div class="container main-content form">
        <div class="content">
            <div class="row">
                <div class="login-form">
                    <h2></h2>
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->

    <!--    footer 开始-->
    <div class="footer hidden-if-in-IFrame">
        <div class="row">
            <div class="span12">
                <p class="pull-left">&copy; <?php echo date('Y'); ?> - <?php echo Yii::app()->name; ?>, All rights
                    reserved.</p>

                <p class="pull-right"><a href="#">Terms of Use</a> &nbsp;|&nbsp; <a href="#">Privacy Policy</a></p>
            </div>
        </div>
    </div>
    <!--    footer结束-->
</div>
</body>
</html>