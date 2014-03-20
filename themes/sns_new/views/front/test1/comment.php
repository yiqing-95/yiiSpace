<?php $model = User::model()->findByPk(UserHelper::getVisitorId());

if ($model !== null):?>
<?php
    $this->widget('comments.widgets.ECommentsListWidget', array(
        'model' => $model,
        'dialogOptions'=>array(
            'width'=>'500',
        )
    ));
    ?>
<?php else: ?>

登陆再评论

<?php endif; ?>