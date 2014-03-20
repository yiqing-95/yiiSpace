<?php $this->beginContent('//layouts/main'); ?>

    <div class="container ">
        <div class="col">
            <div class="menu cell  col">
                <ul class="links nav">
                    <li class="">
                        <a href="#" class="">Latest</a>
                    </li>
                    <li class="active"><a href="#"> Hottest </a></li>
                    <li><a href="#">most comment </a></li>
                </ul>
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