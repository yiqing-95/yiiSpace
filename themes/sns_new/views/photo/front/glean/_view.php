
<li class="span3 view">
    <div class="thumbnail">
        <div align="center">
            <a class="thumb" name="leaf"
               href="<?php echo $data->getViewUrl(); ?>" title="Title #0">
                <img src="<?php echo $data->getThumbUrl(); ?>" alt="Title #0"/>
            </a>
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