<?php   $user = $data->user ;  ?>
<li class="span3 view">
    <div class="thumbnail">
        <div align="center">
            <a  href="<?php echo UserHelper::getUserSpaceUrl($user->primaryKey) ?>"
                target="_blank"
                class="user-simple-box"
                data-profile-box-url="<?php echo Yii::app()->createUrl('/user/ajaxProfileBox',array('u'=>$user->primaryKey)) ?>"
                >
                <img src="<?Php echo $user->getIconUrl(); ?>"
                     width="120px" height="120px"
                     alt=""/>
            </a>
            <?php echo CHtml::encode($user->username) ?>

        </div>
        <div >

            <?php if (UserHelper::getIsOwnSpace()): ?>
            <?php
            /*
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
               'htmlOptions'=>array(),
                'buttons' => array(
                    array('label' => '编辑', 'url' => array(PhotoHelper::getEditAlbumRoute(), 'id' => $data->id),
                        'htmlOptions' => array('class' => 'update btn-mini',)
                    ),
                    array('label' => '删除', 'url' => array(PhotoHelper::getDeleteAlbumRoute(), 'id' => $data->id),
                        'htmlOptions' => array('class' => 'delete btn-mini'),),
                )));
                */
            ?>
        </div>
        <?php endif; ?>
    </div>

</li>
