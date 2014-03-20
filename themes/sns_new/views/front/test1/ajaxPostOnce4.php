<h3>
    这个是用来演示快速点击ajax链接 忽略当前请求的例子！
</h3>

<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午1:13
 */
// YsAjaxHandler::registerClientErrorHandler('#ajax_error_container');
?>

<?php
echo CHtml::ajaxLink('测试ajax请求', Yii::app()->controller->createUrl(''), array( /*
        'error'=>'js:function(){
            alert("hhah");
        }',
        */
    'type'=>'POST',
));
?>

<script type="text/javascript">
    // jQuery.ajaxSetup( {}, {global:true} );

    $(document).ready(function () {

        // 用来存储已经触发的请求url 其值如果是true 表示正在进行ajax请求
        // 只处理post请求哦！
        var  _requestsCache = {};

        jQuery(document).ajaxStart(function () {
           // alert("ajaxStart");
        }).ajaxStop(function () {
               // alert("ajaxStop");
                // }).ajaxSend(function(jqXHR, s){
            }).ajaxSend(function (event, xhr, options) {
               // alert("ajaxSend");

                if (options.type.toUpperCase() == 'POST' &&  _requestsCache[options.url]) {
                    xhr.abort(); //  _requestsCache[options.url].abort();
                    console.log("waiting for completing !")
                }
                _requestsCache[options.url] = true;

                /*
                 if(options.requestKey &&  confirm("要不要终止？")){
                 xhr.abort();
                 alert("请求被终止啦！！你的自定义键 是："+options.requestKey);
                 }

                var requestKey = 'request';
                if (locker.isLocked(requestKey)) {
                    alert("锁定了哦");
                    xhr.abort();
                }
                // 没锁定 那么锁下
                else {
                    locker.lock(requestKey);
                }
                */

            }).ajaxComplete(function (event, request, settings) {
               // alert("ajaxComplete");
                _requestsCache[settings.url] = null;
            }).ajaxError(function () {
              //  alert("ajaxError");
            }).ajaxSuccess(function (event, xhr, settings ) {
                alert("ajaxSuccess");
            });


    });

    $(function(){
        $('#AjaxTestButton').click(function () {
            var TestObj = new AjaxTest('');
            TestObj.send();
        });
    });

    AjaxTest = function (url) {
        this.url = url;
    }

    AjaxTest.prototype = {
        send: function () {
            jQuery.ajax({
                url: this.url,
                timeout: 1000,
                beforeSend: function () {
                    alert("local beforeSend");
                },
                success: function (data) {
                    alert("success");
                   // $('#AjaxResponse').html(data);
                },
                error: function () {
                    alert("error");
                },
                complete: function () {
                    alert("complete");
                },
                context: this,
                requestKey: 'anyIdHere'
            });
        }
    }

</script>

<div>
    <input id="AjaxTestButton" type="button" name="ajaxtestbutton" value="Start Ajax Test"/>
</div>

<div>
    Loaded by ajax request:
    <div id="AjaxResponse">

    </div>
</div>







