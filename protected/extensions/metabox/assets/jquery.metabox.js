/**
 * Metabox extension
 */

!function( $ ){

    "use strict"

    var Metabox = function ( element, options ) {
        this.$element = $(element)
        this.options = $.extend({}, $.fn.metabox.defaults, options)
        if(this.options.refreshOnInit)
            this.refresh();
        
        if(this.options.refreshTimeout>0)
            this.createTimeout();
        
        else if(this.options.refreshInterval>0)
            this.createInterval();
    }

    Metabox.prototype = {

        constructor: Metabox
        ,
        
        createTimeout : function() {
            var $this = this;
            setTimeout(function(){
                $this.refresh();
            },this.options.refreshTimeout);
        }
        ,
        
        createInterval : function() {
            var $this = this;
            setInterval(function(){
                $this.refresh();
            },this.options.refreshInterval);
        }
        ,
        
        refresh: function (options) {
            var customError;
            if (options && options.error !== undefined) {
                customError = options.error;
                delete options.error;
            }

            var $box = this;
            
            options = $.extend({
                type: 'GET',
                url : this.options.url,
                beforeSend : function() {
                    $box.$element.addClass($box.options.loadingClass);
                    $box.$element.hide().next().show();
                    $box.options.beforeRefresh.apply($box);
                },
                success : function(data) {
                    $box.$element.removeClass($box.options.loadingClass);
                    $box.$element.show().next().hide();
                    $box.$element.html(data);
                    $box.options.afterRefresh.apply($box, [data]);
                },
                error: function (XHR, textStatus, errorThrown) {
                    var ret, err;
                    $box.$element.removeClass($box.options.loadingClass);
                    $box.$element.show().next().hide();
                    if (XHR.readyState === 0 || XHR.status === 0) {
                        return;
                    }
                    if (customError !== undefined) {
                        ret = customError(XHR);
                        if (ret !== undefined && !ret) {
                            return;
                        }
                    }
                    switch (textStatus) {
                        case 'timeout':
                            err = 'The request timed out!';
                            break;
                        case 'parsererror':
                            err = 'Parser error!';
                            break;
                        case 'error':
                            if (XHR.status && !/^\s*$/.test(XHR.status)) {
                                err = 'Error ' + XHR.status;
                            } else {
                                err = 'Error';
                            }
                            if (XHR.responseText && !/^\s*$/.test(XHR.responseText)) {
                                err = err + ': ' + XHR.responseText;
                            }
                            break;
                    }

                    if (err)
                        alert(err);
                }
            }, options || {});
            if (options.data !== undefined && options.type === 'GET') {
                options.url = $.param.querystring(options.url, options.data);
                options.data = {};
            }
            $.ajax(options);
        }

    }


    /* METABOX PLUGIN DEFINITION
  * ======================== */

    $.fn.metabox = function ( option, ajaxoptions ) {
        return this.each(function () {
            var $this = $(this)
            , data = $this.data('metabox')
            , options = typeof option == 'object' && option
            if (!data) $this.data('metabox', (data = new Metabox(this, options)))
            
            if (option == 'refresh') data.refresh(ajaxoptions)
        })
    }

    $.fn.metabox.defaults = {
        loadingText: 'loading...',
        loadingClass: 'metabox-loading',
        url: null,
        refreshOnInit : false,
        refreshTimeout : 0,
        refreshInterval : 0,
        beforeRefresh: function() {},
        afterRefresh: function(data) {}
    }

    $.fn.metabox.Constructor = Metabox

    /* METABOX DATA-API
  * =============== */

    $(function () {
        })

}( window.jQuery );