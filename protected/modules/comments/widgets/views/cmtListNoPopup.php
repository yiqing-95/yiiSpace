<div class="comment-widget" id="<?php echo $this->id; ?>" object_id="<?php echo $this->objectId;?>">
    <h3><?php echo Yii::t('CommentsModule.msg', 'Comments');?></h3>
    <?php

    //$this->render('cmtTopLevelList', array('comments' => $dataProvider->getData()));
    $this->widget('zii.widgets.CListView', array(
        'itemsTagName' => 'ul',
        'itemsCssClass' => 'comments-list',
        'emptyText' => Yii::t('CommentsModule.msg', 'No comments'),
        'id' => 'cmt-list' . $this->id,
        'dataProvider' => $dataProvider,
        'itemView' => '_cmtView',
        'beforeAjaxUpdate' => 'js:function(id,options){
          Comment.restoreForm();
        }',
    ));


    if ($this->registeredOnly === false || Yii::app()->user->isGuest === false) {
        // echo CHtml::link(Yii::t('CommentsModule.msg', 'Add comment'), '#', array('rel'=>0, 'class'=>'add-comment'));
    } else {
        echo '<strong>' . Yii::t('CommentsModule.msg', 'You cannot add a new comment') . '</strong>';
    }
    ?>
    <div class="cmt-form">
        <?php
        if ($this->registeredOnly === false || Yii::app()->user->isGuest === false) {
            // echo CHtml::link(Yii::t('CommentsModule.msg', 'Add comment'), '#', array('rel'=>0, 'class'=>'add-comment'));
            $this->widget('comments.widgets.ECommentsFormWidget', array(
                'objectName' => $this->objectName,
                'objectId' => $this->objectId,
                'isReplyForm' => false,
                'isPopupForm' => $this->showPopupForm,
            ));
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    var firstPageUrl = "<?php echo $dataProvider->getPagination()->createPageUrl(Yii::app()->getController(), 0); ?>";
    var cmtListId = "<?php echo 'cmt-list' . $this->id; ?>";
    function refreshCmtList() {
        $.fn.yiiListView.update(cmtListId, {url:firstPageUrl});
    }
    function refreshCmtListWithCurrentPage() {
        $.fn.yiiListView.update(cmtListId);
    }

    function submitComment(ele) {
        var $submitBtn = $(ele);
        var $cmtForm = $(ele).closest("form");
        //var $cmtForm = $(this).parents('form');
        //var $cmtForm = $("form", '.cmt-form'); //上门两个傻蛋用不了！
        jQuery.ajax(
            {url:$cmtForm.attr("action"),
                'data':$cmtForm.serialize(),
                'type':'post',
                'dataType':'json',
                'success':function (data) {
                    if (data.code == 'fail') {
                        var $formParent = $cmtForm.parent();
                        $formParent.html(data.form);
                        // Here is the trick: on submit-> once again this function!
                        $('form', $formParent).submit(submitComment);
                    } else {
                        refreshCmtList();
                        alert('评论成功');
                        //不得不加这个 不然还有问题
                        var $formParent = $cmtForm.parent();
                        $formParent.html(data.form);
                        // Here is the trick: on submit-> once again this function!
                        $('form', $formParent).submit(submitComment);
                    }

                }, 'cache':false});
        ;
        return false;

    }

    Comment = {
        moveForm:function (cmtId, parentId) {
            var t = this,
                $cmtBlock = $("#" + cmtId).closest(".comment-widget"),
                $cmtDiv = $("#" + cmtId),

                $cmtFormDiv = $cmtBlock.find(".form"),

                $parentFiled = $(".cmt_parent_id", $cmtFormDiv),

                $tmpFormDiv = $("#cmt-temp-form-div") ,
                $cancelReplyLink = $('#cancel-comment-reply-link'),
                cancelReplyLink = t.elmt('cancel-comment-reply-link');

            if (!t.elmt('cmt-temp-form-div')) {
                $tmpFormDiv = $('<div id="cmt-temp-form-div" style="display: ;">');
            }

            $tmpFormDiv.append($cmtFormDiv);

            $parentFiled.val(parentId);
            $cmtDiv.append($tmpFormDiv);
            //取消按钮显示出来
            cancelReplyLink.style.display = '';
            cancelReplyLink.onclick = function () {
                //取消回复 时需要把评论parentId 置为0
                $parentFiled.val(0);
                $cmtBlock.append($cmtFormDiv);
                // 高亮评论框
                try {
                    $('.comment-content', $cmtFormDiv).get(0).focus();
                } catch (e) {
                }
                this.style.display = 'none';
                this.onclick = null;
                return false;
            }

            try {
                $('.comment-content', $cmtFormDiv).get(0).focus();
            } catch (e) {

            }
            // $cmtBlock.find('.comment-content').focus();
            return false;
        },
        restoreForm:function () {
            var $tmpFormDiv = $("#cmt-temp-form-div");
            if ($('#cancel-comment-reply-link', $tmpFormDiv).length > 0) {
                $('#cancel-comment-reply-link', $tmpFormDiv).click();
            }
        },
        elmt:function (e) {
            return document.getElementById(e);
        }
    }
    $(function () {
        $(".comment-widget").undelegate('.delete','click');
        $(".comment-widget").delegate('.delete', 'click',function () {
            var id = $($(this).parents('.comment-widget')[0]).attr("id");
            if (confirm($.fn.commentsList.settings[id]['deleteConfirmString'])) {
                Comment.restoreForm();
                $.post($(this).attr('href'))
                    .success(function (data) {
                        data = $.parseJSON(data);
                        if (data["code"] === "success") {
                            $("#comment-" + data["deletedID"]).remove();
                        }
                    });
            }
            return false;
        }).delegate('.add-comment', 'click', function () {
                var commentID = $(this).attr('rel');
                Comment.moveForm("comment-" + commentID, commentID);
                return false;
            });
    });
</script>