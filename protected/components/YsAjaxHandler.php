<?php

/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午12:19
 * @see http://stackoverflow.com/questions/377644/jquery-ajax-error-handling-show-custom-exception-messages
 */
class YsAjaxHandler
{



    /**
     * @return array
     */
    public static function getStatusErrorMap()
    {
        return array(
            '400' => "Server understood the request but request content was invalid.",
            '404' => "The requested page does not exist.",
            '401' => "Unauthorised access.",
            '403' => "Forbidden resource can't be accessed",
            '500' => "Internal Server Error.",
            '503' => "Service Unavailable",
        );
    }

    /**
     * 注册浏览器端处理ajax请求错误的通用处理器
     *
     * @param string $errorContainer
     *
     * ajaxError signature：
     *      function (event, XMLHttpRequest, ajaxOptions, thrownError) {
     *               // thrownError 只有当异常发生时才会被传递
     *              this; // 监听的 dom 元素
     *       }
     * @param array $displayOptions 错误出现时错误容器的css特征 TODO 暂未考虑！
     */
    public static function registerClientErrorHandler($errorContainer = 'body',$displayOptions = array())
    {
        $statusErrorMap = self::getStatusErrorMap() ;
        $statusErrorMapJson = CJSON::encode($statusErrorMap);

        $ajaxErrorHandle = <<<EOD
    jQuery("{$errorContainer}")
    .ajaxError(
        function(e, x, settings, exception) {
        alert("hii");
            var message;
            var statusErrorMap = {$statusErrorMapJson};

            if (x.status) {
                message =statusErrorMap[x.status];
                       if(!message){
                            message= ' Unknow Error .';
                       }else{
                             // ignore the client side error !!!
                             // try to parse the response data as a json string  ;
                            try{
                                var obj = jQuery.parseJSON(x.responseText);
                                // we assume the server always send a "msg" for the error displaying !
                                var message = obj.msg ;
                            }catch(err){
                                // server data is not a validate json string !
                                message = x.responseText ;
                            }
                       }
            }else if(exception=='parsererror'){
                message="Error. Parsing JSON Request failed.";
            }else if(exception=='timeout'){
                message="Request Time out.";
            }else if(exception=='abort'){
                message="Request was aborted by the server";
            }else {
                message="Unknown Error ";
            }
            $(this).css("display","inline");


            $(this).html(message);

            setTimeout(function() {
                   jQuery(this).fadeOut({"opacity":"0"})
            } ,
               5500 + 1000
            );
        });
EOD;

        Yii::app()->clientScript->registerScript(__CLASS__, $ajaxErrorHandle, CClientScript::POS_HEAD);
    }

} 