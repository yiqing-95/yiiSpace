<div class="comment-widget" id="<?php echo $this->id; ?>" object_id="<?php echo $this->objectId ;?>">
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
    ));


    if ($this->showPopupForm === true) {
        if ($this->registeredOnly === false || Yii::app()->user->isGuest === false) {
            echo "<div id=\"addCommentDialog-$this->id\">";
            $this->widget('comments.widgets.ECommentsFormWidget', array(
                'objectName'=>$this->objectName,
                'objectId'=>$this->objectId,
                'isReplyForm'=>true,
                'isPopupForm'=>$this->showPopupForm,
            ));
            echo "</div>";
        }
    }

    if ($this->registeredOnly === false || Yii::app()->user->isGuest === false) {
         echo CHtml::link(Yii::t('CommentsModule.msg', 'Add comment'), '#', array('rel'=>0, 'class'=>'add-comment'));
    } else {
        echo '<strong>' . Yii::t('CommentsModule.msg', 'You cannot add a new comment') . '</strong>';
    }
    ?>
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
        var $cmtForm = $(ele).closest("form") ;
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


</script>