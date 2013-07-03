/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-3-20
 * Time: 上午10:15
 */
(function ($) {
    var  methods,
        cachedSettings = [];
    /**
     * all available methods to public 
     */
    methods = {
        init: function (options) {
            var settings = $.extend({
               activeClass : 'actived'
            }, options || {});

            return this.each(function () {
                var $container = $(this),
                    id = $container.attr('id');
                    //存入全局缓存中
                     cachedSettings[id] = settings;

                   //事件绑定
                   $(settings.target,$container).live('click',function(){
                       var $lastActive  = $("."+settings.activeClass,$container);
                       if($lastActive.length > 0){
                           $lastActive.removeClass(settings.activeClass);
                       }
                       $(this).addClass(settings.activeClass);
                   });
            });
        },

        /**
         * 获取当前活动的目标对象
         */
        getCurrent: function () {
            var settings = cachedSettings[this.attr('id')],
                current = $("."+settings.activeClass,this);
            return current;
        },
        /**
         * 上面方法的别名 由于内容较少直接复制了
         */
        getActive: function () {
            var settings = cachedSettings[this.attr('id')],
                current = $("."+settings.activeClass,this);
            return current;
        }
    };

    $.fn.currentActive = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.currentActive');
            return false;
        }
    };

    /******************************************************************************
     *** DEPRECATED METHODS
     *** used for funny
     ******************************************************************************/
    $.fn.currentActive.settings = cachedSettings;

    $.fn.currentActive.getCurrent = function (id) {
        return $('#' + id).currentActive('getCurrent');
    };
})(jQuery);
