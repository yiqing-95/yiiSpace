<div class="row panel callout radius">
    <div class="two columns">
        <?php UserHelper::renderUserIcon($data->actor); ?>
    </div>

<!---->
<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('id')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
<!--	<br />-->
<!---->
<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('uid')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode($data->uid); ?>
<!--	<br />-->
<!---->
<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('action_type')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode($data->action_type); ?>
<!--	<br />-->
<!---->
<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('action_time')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode($data->action_time); ?>
<!--	<br />-->
<!---->
<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('data')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode($data->data); ?>
<!--	<br />-->
<!---->

    <div class="ten columns">
        <?php
        $feedHandler = ActionFeedManager::getActionFeedHandler($data->object_type);
        if($feedHandler !== null){
            $feedHandler->renderTitle($data->data);
        }
        ?>
    </div>

</div>