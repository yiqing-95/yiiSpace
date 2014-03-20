<div class='portlet'>
    <div class='portlet-decoration'>
        <div class='portlet-title'>
        最近评论
        </div>
    </div>
    <div class='portlet-content'>
        <?php if(isset($models) && $models != array()): ?>
            <ul>
                <?php foreach ($models as $model): ?>
                    <li>
<!--                          此处的title是假的！ 但信息仍可以从model_profile_data中提取出来（json反编码即可）-->
                        <?php echo CHtml::link($model->text,
                            Yii::app()->createUrl('blog/post/view',array('id'=>$model['model_id'],
                                'title'=>StringUtil::cnTruncate($model->text,8,''),
                                    '#'=>'cmt_'.$model->primaryKey,
                            )
                            )
                        );?>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif; ?>
    </div>
</div>
