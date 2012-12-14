;
(function ($) {

    /**
     *
     * @param options
     * @return {*}
     */
    $.fn.ysVoting = function (options) {
        return this.each(function () {
            var settings = $.extend({}, $.fn.ysVoting.defaults, options || {});
            var $this = $(this);
            var id = $this.attr('id');
            $.fn.ysVoting.settings[id] = settings;

            var objectName = $this.attr("object_name"),
                objectId = $this.attr("object_id");
            var url = settings['url'];

            $this.delegate('.thumbUp', 'click', function () {
                if ($this.attr('voted')) {
                    alert(settings.repeatVotingMsg);
                    return;
                } else {
                    $this.attr('voted', '1');
                }

                var rtn = $.fn.ysVoting.postVoting(url, {"objectName":objectName, "objectId":objectId, "mode":"thumbUp"});

                if(rtn == true){
                  var votingValue =  parseInt($(".voting-value",$this).text());
                    $(".voting-value",$this).text(1+votingValue);
                    if($(".up-value",$this).size()>0){
                        var upValue =  parseInt($(".up-value",$this).text());
                        $(".up-value",$this).text(1+upValue);
                    }
                    if($(".total-votes",$this).size()>0){
                        var upValue =  parseInt($(".total-votes",$this).text());
                        $(".total-votes",$this).text(1+upValue);
                    }
                }
                return false;
            })
                .delegate('.thumbDown', 'click', function () {
                    if ($this.attr('voted')) {
                        alert(settings.repeatVotingMsg);
                        return;
                    } else {
                        $this.attr('voted', '1');
                    }

                    var rtn =  $.fn.ysVoting.postVoting(url, {"objectName":objectName, "objectId":objectId, "mode":"thumbDown"});
                    if(rtn == true){
                        var votingValue =   parseInt($(".voting-value",$this).text());
                        $(".voting-value",$this).text(0+votingValue-1);
                        if($(".down-value",$this).size()>0){
                            var upValue =  parseInt($(".down-value",$this).text());
                            $(".down-value",$this).text(1+upValue);
                        }
                        if($(".total-votes",$this).size()>0){
                            var upValue =  parseInt($(".total-votes",$this).text());
                            $(".total-votes",$this).text(1+upValue);
                        }
                    }

                    return false;
                })
                .delegate('.plus', 'click', function () {

                    if ($this.attr('voted')) {
                        alert(settings.repeatVotingMsg);
                        return;
                    } else {
                        $this.attr('voted', '1');
                    }

                     var rtn =  $.fn.ysVoting.postVoting(url, {"objectName":objectName, "objectId":objectId, "mode":"thumbUp"});

                    if(rtn == true){
                        var votingValue =   parseInt($(".voting-value",$this).text());
                        $(".voting-value",$this).text(1+votingValue);
                    }

                    return false;
                });
        });
    };

    /**
     *
     * @type {Object}
     */
    $.fn.ysVoting.defaults = {
        repeatVotingMsg:'You already voted',
        url:""
    };

    /**
     *
     * @type {Object}
     */
    $.fn.ysVoting.settings = {};

    /**
     *
     * @param url
     * @param params
     * @return {*}
     */
    $.fn.ysVoting.postVoting = function (url, params) {
        var rtn;
        var response = $.ajax({
            type:"POST",
            url:url,
            data:params,
            cache:false,
            dataType:"json",
            async:false
        }).responseText;
        response = $.parseJSON(response);
        alert(response.msg);
        if (response.status == 'success') {
            rtn = true;
        } else {
            rtn = false;
        }
        /*
         $.post(
         url,
         params,
         function(data){
         alert(data.msg);
         if(data.status=='success'){
         rtn = true;
         }else{
         rtn = false;
         }
         },'json');
         */
        return rtn;
    }

})(jQuery);
