<?php $this->beginContent('//layouts/main'); ?>

    <div class="container site-body ">

        <div class="col">
            <div class="cell">
                <div class="col">
                    <div class="col width-1of5 cell">
                        <img src="<?php echo UserHelper::getSpaceOwnerIconUrl(); ?>" width="64px" height="64px">
                    </div>
                    <div class="col width-3of5">
                        <div class="cell">
                            <div class="menu cell page-sub-menu  ">
                                <ul class="bottom nav">
                                    <?php
                                    $topNavList = YsNavSystem::getUserSpaceNav('top_nav');

                                    foreach ($topNavList as $fromModule => $moduleMenuConfig):
                                        foreach ($moduleMenuConfig as $menuKey => $menuConfig):
                                            ?>

                                            <li class="">
                                                <?php
                                                $menuConfig['url']['u'] = UserHelper::getSpaceOwnerId();
                                                echo CHtml::link($menuConfig['text'], $menuConfig['url']);
                                                ?>
                                            </li>

                                        <?php
                                        endforeach;
                                    endforeach;
                                    ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col width-fill">
                        <div class="cell">
                            <div class="cell menu">
                                <ul class="links nav">
                                    <?php if(!user()->getIsGuest()): ?>
                                        <li class="">
                                            <a href="<?php echo UserHelper::getUserSpaceUrl(Yii::app()->user->getId()); ?> ">我的空间</a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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

                <div class="col">
                    <div class="menu cell">
                        <ul class="nav">

                                <?php
                                $topNavList = YsNavSystem::getUserCenterNav('user_glean_nav');

                                foreach ($topNavList as $fromModule => $moduleMenuConfig):
                                    foreach ($moduleMenuConfig as $menuKey => $menuConfig):
                                        $liHtmlOptions = array() ;
                                        if(isset($menuConfig['htmlOptionsExpression'])){
                                            $liHtmlOptions = Yii::app()->evaluateExpression($menuConfig['htmlOptionsExpression']);
                                        }

                                        ?>

                                        <li <?php echo CHtml::renderAttributes($liHtmlOptions); ?> >
                                            <?php
                                            $menuConfig['url']['u'] = UserHelper::getSpaceOwnerId();
                                            echo CHtml::link($menuConfig['text'], $menuConfig['url']);
                                            ?>
                                        </li>

                                    <?php
                                    endforeach;
                                endforeach;
                                ?>
                        </ul>
                    </div>
                </div>

                <?php echo $content; ?>
            </div>

        </div>

    </div>
<?php $this->endContent(); ?>