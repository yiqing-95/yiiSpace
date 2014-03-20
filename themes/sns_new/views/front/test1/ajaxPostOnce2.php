<script type="text/javascript">
    /* *
     * jQuery Ajax 防止重复提交
     * @author : suntiger035
     * @data   : 2012-5-31 17:13
     */

    ( function($){
        var $ajax = $.ajax;
        $ajax._reuqestsCache = {};
        // 设置全局 AJAX 默认选项。
        $.ajaxSetup({
            mode: "block",
            index: 0,
            cache:  false,
            beforeSend:  function(xhr, s) {
                if (s.mode) {
                    if (s.mode === "abort" && s.index) {
                        if ($ajax._reuqestsCache[s.index]) {
                            $ajax._reuqestsCache[s.index].abort();
                        }
                    }
                    $ajax._reuqestsCache[s.index] = xhr;
                }
            }
        });

        // 这里我是重写了getJSON方法，当然了，这名字随便你改，别覆盖jQuery本身的就可以了

        $.extend({
            getJSON:  function(url, data, callback, options) {
                options = $.extend({}, arguments[arguments.length - 1] || {});
                if (options.mode === "block" && options.index) {
                    if ($ajax._reuqestsCache[options.index]) {
                        return  false;
                    }
                    $ajax._reuqestsCache[options.index] =  true;
                }
                if (options.crossDomain) {
                    options.dataType = "jsonp";
                }
                var type = "json";
                if ($.isFunction(data)) {
                    callback = data;
                    data =  null;
                }
                options = $.extend({
                    type: "GET",
                    url: url,
                    data: data,
                    success: callback,
                    dataType: "json"
                }, options);
                return $.ajax(options);
            }
        });

        $(document).ajaxComplete( function(a,b,c){
            if (c.index) $ajax._reuqestsCache[c.index] =  null;
        })

    })(jQuery);
</script>

<h4>
    <a href="http://www.cfanz.cn/?c=article&a=read&id=21549">
        jQuery Ajax 防止重复提交
    </a>
</h4>

<div>
    增加的参数描述

    jQuery ajax原本的参数不变，增加了，index,mode,crossdomain 三个参数(jQuery 1.5增加了crossdomain，这里保留为了向后兼容)

    index : 每个请求的索引，默认为0，任何值，

    mode ：请求模式，有两个值，“abort”,“block”

    abort : 将之前的请求abort掉，

    block : 将之后的请求abort掉。

    crossdomain : true时候，为jsonp请求，跨域
</div>

<div>
    方法描述$.getJSON()，用法跟原本的getJSON方法一致，只不过，我增加了一个参数，参数设置，始终是最后一个参数
    说明如上。




    测试代码
    <input type="button" id="btn" value="click me" />
    <script type="text/javascript">

        $("#btn").click( function(){

            $.getJSON('handle/try-1.php', {aa:11}, function(data){
                console.log(data);
            },{
                mode : 'block',
                index : "111111111"
            });

            $.getJSON('handle/try-1-1.php', {aa:11}, function(data){
                console.log(data);
            });

        });
    </script>





    demo---mode : "block"

    $.getJSON('handle/try-1.php', {aa:11}, function(data){
    console.log(data);
    },{
    mode : 'block',
    index : "111111111"
    });



    请求显示：

    data




    demo---mode : "abort"

    $.getJSON('handle/try-1.php', {aa:11}, function(data){
    console.log(data);
    },{
    mode : 'abort',
    index : "111111111"
    });

</div>