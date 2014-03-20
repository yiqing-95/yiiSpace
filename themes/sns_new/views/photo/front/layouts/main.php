<?php $this->beginContent('//layouts/main'); ?>

    <div class="container ">
        <div class="col">
            <div class="menu cell size5of7 col">
                <ul class="links nav">
                    <li class="">
                        <a href="#" class="">Latest</a>
                    </li>
                    <li class="active"><a href="#"> Hottest </a></li>
                    <li><a href="#">most comment </a></li>
                </ul>
            </div>
            <div class="menu cell sizefill col">
                <div class="cell">
                    <ul class="links nav">
                        <?php if(!user()->getIsGuest()): ?>
                        <li class="">
                            <a href="<?php echo $this->createUrl('/blog/my/create'); ?>" class="">创建博文</a>
                        </li>
                        <li class="">
                            <a href="<?php echo $this->createUrl('/blog/my'); ?> ">我的博文</a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="container site-body">


        <div class="cell">

            <?php if (Layout::hasRegion('top')): ?>
                <?php Layout::renderRegion('top'); ?>
            <?php endif; ?>

            <?php echo $content; ?>

        </div>

    </div>

<?php $this->endContent(); ?>