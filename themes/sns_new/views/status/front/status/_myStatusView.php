<?php  $statusHandler = StatusManager::getStatusTypeHandler($data['type']);
$statusHandler->data = $data;
$statusHandler->actorLink = CHtml::link(UserHelper::getLoginUserModel()->username,UserHelper::getUserSpaceUrl($data['creator']));

$statusHandler->init();
?>

<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col width-fit">
                    <div class="cell">
                        <img src="<?php echo UserHelper::getLoginUserModel()->getIconUrl() ; ?>" width="75" height="75" alt="">
                    </div>
                </div>
                <div class="col width-fill">


                    <div class="cell">
<!--                          <a href="#">--><?php //echo UserHelper::getLoginUserModel()->username ; ?><!-- </a>-->

                        <?php $statusHandler->renderTitle() ; ?>
                        <div class="cell">
                            <?php  //StatusManager::processTypeStatus($data); ?>
                            <?php $statusHandler->renderBody() ; ?>
                        </div>


                    </div>

                </div>
            </div>
            <div class="divider"></div>
            <p class="float-right">
                <?php //echo CHtml::link(CHtml::encode($data['id']), array('view', 'id' => $data['id'])); ?>
                 <span class="">
                <?php echo  WebUtil::timeAgo2(strtotime($data['created'])); ?>
                     </span>
            </p>
        </div>
    </div>
</div>