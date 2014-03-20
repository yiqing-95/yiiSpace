<?php
/* @var $this CommentsListWidget */
/* @var $data Comment */
$comment = $data;

?>

<div class="col comment-item">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="" style="margin-left: <?php echo(10 * (int)$data->getIndentLevel()); ?>px; "
                     level="<?php echo $data->level; ?>">
                    <div class="cell">
                        <div class="col width-fit">
                            <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
                            <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
                            <br/>
<!--                            如果是本站用户评论那么渲染用户图像  -->
                            <?php if( !empty($data->user_id)): ?>
                                <?php

                                if($userProfiles[$data->user_id]): ?>

                                    <div class="cell">
                                        <a href="<?php echo UserHelper::getUserSpaceUrl($data->user_id); ?>" target="_blank">

                                            <img src="<?php  echo UserHelper::getUserIconUrl($userProfiles[$data->user_id]); ?>"
                                                 width="75" height="75" alt="">
                                        </a>
                                    </div>
                                <?php endif ; ?>
                            <?php endif ; ?>

                        </div>
                        <div class="col width-fill">
                            <div class="cell">
                                <b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
                                <?php echo CHtml::encode($data->parent_id); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
                                <?php echo CHtml::encode($data->user_id); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
                                <?php echo CHtml::encode($data->model); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('model_id')); ?>:</b>
                                <?php echo CHtml::encode($data->model_id); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('url')); ?>:</b>
                                <?php echo CHtml::encode($data->url); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
                                <?php echo CHtml::encode($data->create_time); ?>
                                <br/>


                                <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
                                <?php echo CHtml::encode($data->name); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
                                <?php echo CHtml::encode($data->email); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
                                <?php echo CHtml::encode($data->text); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
                                <?php echo CHtml::encode($data->status); ?>
                                <br/>

                                <b><?php echo CHtml::encode($data->getAttributeLabel('ip')); ?>:</b>
                                <?php echo CHtml::encode($data->ip); ?>
                                <br/>
                                <?php /*
    <b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
    <?php echo CHtml::encode($data->level); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('root')); ?>:</b>
    <?php echo CHtml::encode($data->root); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('lft')); ?>:</b>
    <?php echo CHtml::encode($data->lft); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('rgt')); ?>:</b>
    <?php echo CHtml::encode($data->rgt); ?>
    <br />

    */
                                ?>
                                <?php //  echo __FILE__ ?>
                                <div class="model-profile">
                                    <?php if(!empty($data->model_owner_id)): ?>
                                        <?php
                                        if($userProfiles[$data->model_owner_id]): ?>
                                            <div class="cell">
                                                <a href="<?php echo UserHelper::getUserSpaceUrl($data->model_owner_id); ?>" target="_blank">
                                                 @<?php echo $userProfiles[$data->model_owner_id]['username'] ?>
                                                </a>
                                            </div>
                                        <?php endif ; ?>
                                    <?php endif ;?>
                                    <?php CommentModelProfilerManager::renderModelSummary($data->model,CJSON::decode($data->model_profile_data)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php //  echo YiiUtil::getPathOfClass($this); ?>
            </div>

        </div>
    </div>

</div>