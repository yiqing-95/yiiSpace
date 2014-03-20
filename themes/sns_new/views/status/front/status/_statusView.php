<?php  $statusHandler = StatusManager::getStatusTypeHandler($data['type']);
//print_r($statusHandler);
$statusHandler->data = $data;
$statusHandler->actorLink = CHtml::link($data['username'],UserHelper::getUserSpaceUrl($data['creator']));

$statusHandler->init() ;
?>
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col width-fit">
                    <div class="cell">
                        <a href="<?php echo UserHelper::getUserSpaceUrl($data['creator']); ?>">
                            <img src="<?php echo UserHelper::getUserIconUrl($data); ?>" width="75" height="75" alt="">
                        </a>
                    </div>
                </div>
                <div class="col width-fill">

                    <div class="cell">
                      <?php $statusHandler->renderTitle() ; ?>
                        <div class="cell">
                            <?php $statusHandler->renderBody() ; ?>
                        </div>
                        <?php // print_r($data);?>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <p class="float-right">
                <?php //echo CHtml::link(CHtml::encode($data['id']), array('view', 'id' => $data['id'])); ?>
                <?php echo WebUtil::timeAgo2(strtotime($data['created'])); ?>
            </p>
        </div>
    </div>
</div>