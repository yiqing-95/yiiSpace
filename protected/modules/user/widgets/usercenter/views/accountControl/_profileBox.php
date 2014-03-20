<?php YsPageBox::beginPanel();?>
    <div class="col">
        <div class="cell ">
            <figure class="nuremberg">
                <img src="<?php echo $user->getIconUrl(); ?>" alt="" width="100px"
                     height="100px">
                <figcaption>Efteling</figcaption>
            </figure>
        </div>
        <div class="cell">
            <ul class="nav">
                <li>
                    <?php echo $user->getAttributeLabel('usernam') ?>:
                    <?php echo CHtml::encode($user->username); ?>
                </li>
                <li>
                    注册时间：<?php echo Yii::app()->dateFormatter->format('y-m-d', $user->create_at); ?>
                </li>
                <li>
                    <?php echo CHtml::encode($user->getAttributeLabel('create_at')); ?>:
                    <?php echo $user->create_at; ?>

                </li>
                <li>
                    <?php echo CHtml::encode($user->getAttributeLabel('lastvisit_at')); ?>:
                    <?php echo $user->lastvisit_at; ?>
                </li>

            </ul>
        </div>

    </div>
<?php YsPageBox::endPanel(); ?>
