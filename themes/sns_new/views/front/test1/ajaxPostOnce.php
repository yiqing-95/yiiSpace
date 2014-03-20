<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午1:13
 */
YsAjaxHandler::registerClientErrorHandler('#ajax_error_container');
?>

<div id="ajax_error_container">

</div>
<div id="msg">

</div>
<?php
echo CHtml::ajaxLink('测试ajax请求', Yii::app()->controller->createUrl(''), array( /*
        'error'=>'js:function(){
            alert("hhah");
        }',
        */
));
?>

<script type="text/javascript">
    // jQuery.ajaxSetup( {}, {global:true} );

    $(document).ready(function () {


        var locker = new ERequestLocker();

        jQuery(document).ajaxStart(function () {
            alert("ajaxStart");
        }).ajaxStop(function () {
                alert("ajaxStop");
                // }).ajaxSend(function(jqXHR, s){
            }).ajaxSend(function (event, xhr, options) {
                alert("ajaxSend");
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

            }).ajaxComplete(function () {
                alert("ajaxComplete");
            }).ajaxError(function () {
                alert("ajaxError");
            }).ajaxSuccess(function () {
                alert("ajaxSuccess");
            });

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
                    alert("zapme beforeSend");
                },
                success: function (data) {
                    alert("success");
                    $('#AjaxResponse').html(data);
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

    /**
     *  这个 是设置全局ajax
     */
    ;(function ($) {
        var $ajax = $.ajax;
        $ajax._reuqestsCache = {};
        // 设置全局 AJAX 默认选项。
        $.ajaxSetup({
            beforeSend: function (xhr, s) {
                if ($ajax._reuqestsCache[s.url]) {
                    $ajax._reuqestsCache[s.url].abort();
                    console.log("waiting for completing !")
                }
                $ajax._reuqestsCache[s.url] = xhr;
            }
        });

        $(document).ajaxComplete(function (event, request, settings) {
            $ajax._reuqestsCache[settings.url] = null;
        })

    })(jQuery);

    ERequestLocker = function () {
        this.locks = {};
    }
    ERequestLocker.prototype = {
        /**
         * @param string key
         * @param int lifeTime
         * @return bool is the lock successfully added to the key ?
         */
        lock: function (key, lifeTime) {
            if (this.locks[key] && parseInt(this.locks[key]) > (new Date()).getTime()) {
                return false;
            } else {
                lifeTime = lifeTime || 10;
                lifeTime = lifeTime * 1000;
                this.locks[key] = (new Date()).getTime() + lifeTime;
            }
        },

        /**
         * @param string key
         * @return bool check if the key has been locked ?
         */
        isLocked: function (key) {
            return !!(this.locks[key] && parseInt(this.locks[key]) > (new Date()).getTime());
        },

        /**
         * unlock the specified key
         *
         * @param string key
         * @return void|bool
         */
        unlock: function (key) {
            delete this.locks[key];
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


<script type="text/javascript">
    $(document).ajaxStart(function () {
        $(".log").text("Triggered ajaxStart handler.");
    });
    $(function () {

        $(document).ajaxStart(function () {
            $("#loading").show();
        });
    });
</script>




