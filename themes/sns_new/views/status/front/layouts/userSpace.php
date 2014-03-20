<?php $this->beginContent('//layouts/main'); ?>
    <div class="container site-body">

        <div class="cell">
            <div class="col width-1of4">
                <?php $spaceOwnerModel = UserHelper::getSpaceOwnerModel();

                ?>

                <div class="cell">
                    <?php YsPageBox::beginPanel() ; ?>
                     <div class="col">
                            <div class="cell " >
                                <figure class="nuremberg">
                                    <img src="<?php echo $spaceOwnerModel->getIconUrl(); ?>" alt="" width="100px" height="100px">
                                    <figcaption>Efteling</figcaption>
                                </figure>
                            </div>
                         <div class="cell">
                               <ul class="nav">
                                   <li>
                                       <?php echo $spaceOwnerModel->getAttributeLabel('usernam') ?>:
                                       <?php echo CHtml::encode($spaceOwnerModel->username); ?>
                                   </li>
                                   <li>
                                       注册时间：<?php echo Yii::app()->dateFormatter->format('y-m-d',$spaceOwnerModel->create_at); ?>
                                   </li>

                               </ul>
                         </div>

                     </div>
                    <?php YsPageBox::endPanel() ; ?>

                    <?php  YsPageBox::beginPanel(array('template'=>'{header}{body}','header'=>'最近访客') ); ?>
                    <div class="cell">
                        <?php  $this->widget('user.widgets.4cascadeFr.latestVisitors.LatestVisitors', array(
                            'spaceId' => $spaceOwnerModel->primaryKey,
                            'maxCount' => 9,
                        ));  ?>

                    </div>
                    <?php  YsPageBox::endPanel() ;?>

                    <?php  YsPageBox::beginPanel(array('template'=>'{header}{body}','header'=>'最近空间访问统计') ); ?>
                    <div class="cell">
                        <?php  $this->widget('user.widgets.4cascadeFr.SpaceVisitStatBox', array(
                            'spaceOwnerId' => $spaceOwnerModel->primaryKey,
                        ));  ?>

                    </div>
                    <?php  YsPageBox::endPanel() ;?>
                </div>

            </div>
            <div class="col width-fill">
                <?php echo $content; ?>
            </div>
        </div>

    </div>
<?php $this->endContent(); ?>