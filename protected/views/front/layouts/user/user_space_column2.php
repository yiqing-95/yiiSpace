<?php $this->beginContent('//layouts/user/user_space'); ?>

<div class="row-fluid">
    <div class="span12">
        <!--        Fluid 12-->
        <?php
        if (!empty($this->menu)) :
            $this->widget("bootstrap.widgets.TbMenu", array('items' => $this->menu, 'type' => 'pills'));
        endif;
        ?>
        <div class="row-fluid">
            <div class="span9">
                <?php echo $content; ?>
            </div>
            <div class="span3" style="">
<!--                最近来访-->
                <h3>最近来访：</h3>
                <?php for ($j = 0; $j < 3; $j++): ?>
                <div class="row-fluid span12">
                    <ul class="thumbnails">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                        <li class="span3">
                            <a href="#" class="thumbnail">
                                <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                            </a>
                            <p>
                            </p>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </div>
                <?php endfor; ?>
                <!--                最近来访 end-->

                <div class="row-fluid span12">
                    <h3>-------</h3>
                    <?php $profile->renderSidebar(); ?>
                </div>


            </div>
        </div>
    </div>
</div>



<?php $this->endContent(); ?>