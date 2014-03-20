<?php //$this->widget('UserMenu'); ?>

<?php $this->beginContent('/layouts/main'); ?>

    <div class="cell">

        <div class="col">
            <div class="col width-4of5">
                <div class="cell">
                    <?php echo $content; ?>
                </div>
            </div>

            <div class="col width-1of5">
                <?php YsPageBox::beginPanelWithHeader('操作') ;?>
                <div class="menu cell">
                    <?php
                    if (!empty($this->menu)) {
                        $userActions = $this->widget('zii.widgets.CMenu', array(

                            'items' => $this->menu,
                            'htmlOptions' => array('class' => 'left links nav'),
                        )
                        );
                    }
                    ?>
                </div>
                <?php YsPageBox::endPanel(); ?>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>