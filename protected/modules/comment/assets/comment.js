/**
 * jQuery Comments list plugin
 * @author yiqing <yiqing_95@qq.com>
 */

;
(function($) {
    /**
	 * commentsList set function.
	 * @param options map settings for the comments list. Available options are as follows:
	 * - deleteConfirmString
         * - approveConfirmString
	 */
    $.fn.commentsList = function(options) {
        return this.each(function(){
            var settings = $.extend({}, $.fn.commentsList.defaults, options || {});
            var $this = $(this);
            var id = $this.attr('id');
            $.fn.commentsList.settings[id] = settings;

            $this
            .delegate('.delete', 'click', function(){
                var id = $($(this).parents('.comment-widget')[0]).attr("id");
                if(confirm($.fn.commentsList.settings[id]['deleteConfirmString']))
                {
                    $.post($(this).attr('href'))
                    .success(function(data){
                        data = $.parseJSON(data);
                        if(data["code"] === "success")
                        {
                            $("#comment-"+data["deletedID"]).remove();
                        }
                    });
                }
                return false;
            })
            .delegate('.approve', 'click', function(){
                var id = $($(this).parents('.comment-widget')[0]).attr("id");
                if(confirm($.fn.commentsList.settings[id]['deleteConfirmString']))
                {
                    $.post($(this).attr('href'))
                    .success(function(data){
                        data = $.parseJSON(data);
                        if(data["code"] === "success")
                        {
                            $("#comment-"+data["approvedID"]+" > .admin-panel > .approve").remove();
                        }
                    });
                }
                return false;
            })
            .delegate('.add-comment', 'click', function(){
                var id = $($(this).parents('.comment-widget')[0]).attr("id");
                $dialog = $("#addCommentDialog-"+id);
                var commentID = $(this).attr('rel');
                if(commentID)
                    $('.cmt_parent_id', $dialog).val(commentID);

                $dialog.dialog("open");

                return false;
            });
        });
    };
        
    $.fn.commentsList.defaults = {
        deleteConfirmString: 'Delete this comment?',
        approveConfirmString: 'Approve this comment?',
        postButton: 'Add comment',
        cancelButton: 'Cancel'
    };
        
    $.fn.commentsList.settings = {};
        


})(jQuery);