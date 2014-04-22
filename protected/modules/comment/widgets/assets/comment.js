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

            // delete comment handler
            jQuery(document).on('click', '#'+ id +' a.delete',function() {
                if(!confirm($.fn.commentsList.settings[id]['deleteConfirmString'])) return false;

                var deleteLink = this ;

                $.post($(deleteLink).attr('href'))
                    .success(function(data){
                        data = $.parseJSON(data);
                        if(data.status === "success")
                        {
                           // $("#comment-"+data["approvedID"]+" > .admin-panel > .approve").remove();
                            alert('删除成功');
                            $(deleteLink).closest('.comment-item').empty().remove();
                        }
                    },'json');
                return false;

            });

            // approve this comment
            // delete comment handler
            jQuery(document).on('click', '#'+ id +' a.approve',function() {
                if(!confirm($.fn.commentsList.settings[id]['approveConfirmString'])) return false;

                var cmdLink = this ;

                $.post($(cmdLink).attr('href'))
                    .success(function(data){
                        data = $.parseJSON(data);
                        if(data.status === "success")
                        {
                            // $("#comment-"+data["approvedID"]+" > .admin-panel > .approve").remove();
                            alert('操作成功');
                            $(deleteLink).remove();
                        }
                    });
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