/**
 * Metabox javascript class.
 * 
 * @author Yannis Fragkoulis <yannis.fragkoulis@gmail.com>
 * @version 0.4
 */

!function( $ ){

    "use strict";

    var Metabox = function ( element, options ) {
        this.options = $.extend({}, $.fn.metabox.defaults, options);
        this.$element = $(element);
		this.$content = $('.' + this.options.cssClass + '-content', element);
        this.$header = $('.' + this.options.cssClass + '-header', element);
        this.$footer = $('.' + this.options.cssClass + '-footer', element);
        
        this.options.errorText = this.options.errorText.replace('{url}', this.options.url);
        
        if(this.options.loadingContainer)
            this.options.loadingContainer = $(this.options.loadingContainer);
        else
            this.options.loadingContainer = this.$content;
    
        if(this.options.refreshTimeout>0)
            this.createTimeout();
        
        else if(this.options.refreshInterval>0)
            this.createInterval();
        
        if(this.options.refreshOnInit)
            this.refresh();
    };

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
                type: this.options.type,
                url : this.options.url,
                data : this.options.data,
                beforeSend : function() {
                    $box.$element.addClass($box.options.loadingClass);
                    $box.options.loadingContainer.html($box.options.loadingText);
                    $box.options.beforeRefresh.apply($box);
                },
                success : function(data) {
                    $box.$element.removeClass($box.options.loadingClass);
                    $box.options.loadingContainer.html('');
                    $box.options.handleResponse.apply($box, [data]);
                    $box.options.afterRefresh.apply($box, [data]);
                },
                error: function (XHR, textStatus, errorThrown) {
                    var ret, err;
                    $box.$element.removeClass($box.options.loadingClass);
                    $box.options.loadingContainer.html($box.options.errorText);
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

    };


    /* METABOX PLUGIN DEFINITION
  * ======================== */

    $.fn.metabox = function ( option, ajaxoptions ) {
        return this.each(function () {
            var $this = $(this)
            , data = $this.data('metabox')
            , options = typeof option == 'object' && option;
            if (!data) $this.data('metabox', (data = new Metabox(this, options)));
            
            if (option == 'refresh') data.refresh(ajaxoptions);
        })
    };

    $.fn.metabox.defaults = {
        loadingText: 'loading...',
        loadingClass: 'metabox-loading',
        loadingContainer: null,
        errorText: 'Error loading {url}',
		cssClass: 'metabox',
        url: null,
        data : {},
        type : 'GET',
        refreshOnInit : false,
        refreshTimeout : 0,
        refreshInterval : 0,
        beforeRefresh: function() {},
        handleResponse: function(data) {
            this.$content.html(data);
        },
        afterRefresh: function(data) {},
        debug: true
    };

    $.fn.metabox.Constructor = Metabox

}( window.jQuery );