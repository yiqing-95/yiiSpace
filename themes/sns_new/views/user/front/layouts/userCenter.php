<?php $this->beginContent('//layouts/main'); ?>

    <div class="container site-body">

        <div class="cell">
            <div class="col width-1of4">
                <?php $spaceOwnerModel = UserHelper::getLoginUserModel(); ?>
                <div class="cell">
                    <?php YsPageBox::beginPanel();?>
                    <div class="col">
                        <div class="cell ">
                            <figure class="nuremberg">
                                <img src="<?php echo $spaceOwnerModel->getIconUrl(); ?>" alt="" width="100px"
                                     height="100px">
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
                                    注册时间：<?php echo Yii::app()->dateFormatter->format('y-m-d', $spaceOwnerModel->create_at); ?>
                                </li>

                            </ul>
                        </div>

                    </div>
                    <?php YsPageBox::endPanel(); ?>

                    <!--                    个人设置-->
                    <div class="panel cell">
                        <div class="collapsible">
                            <div class="header collapse-trigger">
                                <span class="icon icon-collapse"></span>
                                <a href="#">
                                    <span class="collapsed-only">Show</span>
                                    <span class="uncollapsed-only">Hide</span> panel
                                </a>
                            </div>
                            <div class="body collapse-section" style="display: block;">
                                <div class="cell">
                                    <div class="menu ">
                                        <ul class="right links nav">
                                            <li class="disabled">设置
                                                <ul class="right links nav">
                                                    <li class="">
                                                        <a href="<?php echo Yii::app()->createUrl( '/user/settings/photo'); ?>" class="">
                                                            图像
                                                        </a>
                                                    </li>
                                                    <li class="">
                                                        <!-- 用class=active来高亮当前菜单-->
                                                        <a href="<?php echo Yii::app()->controller->createUrl('/user/gleanList');?>" class="">收藏</a>
                                                    </li>
                                                    <li class="">
                                                        <a href="#" class="">邮箱</a>
                                                    </li>
                                                    <li class="">
                                                        <a href="#" class="">密码</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="">
                                                <a href="#" class="">Normal item</a>
                                            </li>
                                            <li class="">
                                                <!-- 用class=active来高亮当前菜单-->
                                                <a href="#" class="">Active item</a>
                                            </li>

                                            <li class="">
                                                <a href="#" class="">Another normal item</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="collapsible collapsed">
                            <div class="header collapse-trigger">
                                <span class="icon icon-collapse"></span>
                                <a href="#">
                                    <span class="collapsed-only">Show</span>
                                    <span class="uncollapsed-only">Hide</span> panel
                                </a>
                            </div>
                            <div class="body collapse-section" style="display: none;">
                                <div class="cell">
                                    This is a panel body.
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--                    个人设置/-->

                    <?php  YsPageBox::beginPanel(array('template' => '{header}{body}', 'header' => '最近访客')); ?>
                    <div class="cell">
                        <?php  $this->widget('user.widgets.4cascadeFr.latestVisitors.LatestVisitors', array(
                            'spaceId' => $spaceOwnerModel->primaryKey,
                            'maxCount' => 9,
                        ));  ?>

                    </div>
                    <?php  YsPageBox::endPanel();?>

                </div>

            </div>
            <div class="col width-fill">
                <?php  YsPageBox::beginPanel(); ?>
                <?php echo $content; ?>
                <?php  YsPageBox::endPanel();?>
            </div>
        </div>

    </div>

<?php $this->endContent(); ?>