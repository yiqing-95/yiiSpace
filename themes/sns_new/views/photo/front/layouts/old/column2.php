<?php //$this->widget('UserMenu'); ?>

<?php
Layout::addBlock('right', array(
    'id' => 'rightSidebarBlogCategories',
    'content' => $this->widget('BlogCate', array(
        'uid' => 0, //user()->getId(),
    ), true),
    'weight' => -1
));


Layout::addBlock('right', array(
    'id' => 'rightSidebarTagCloud',
    'content' => $this->widget('TagCloud', array(
        'maxTags' => Yii::app()->params['tagCloudCount'],
    ), true),
));

Layout::addBlock('right', array(
    'id' => 'rightSidebarRecentComments',
    'content' => $this->widget('RecentComments', array(
        'maxComments' => Yii::app()->params['recentCommentCount'],
    ), true),
));

Layout::addBlock('right', array(
    'id' => 'rightSidebarMonthlyArchives',
    'content' => $this->widget('MonthlyArchives', array(
        'year' => '年',
        'month' => '月',
        'uid' => user()->getId(),
    ), true),
));
?>

<?php $this->beginContent('/layouts/main'); ?>



    <div class="cell">
        <div class="col">
            <?php
            if (!empty($this->menu)) {
                $userActions = $this->widget('zii.widgets.CMenu', array(
                    'items' => $this->menu,
                    'htmlOptions' => array('class' => 'operations'),
                ), true);
                /*
                $this->widget('bootstrap.widgets.TbNavbar', array(
                    'brand' => false,
                    'fixed' => false,
                    'items' => array(
                        "<form class=\"navbar-form pull-right\">
                {$userActions}
            </form>"
                    )
                ));
                */
            }

            ?>
        </div>
        <div class="col">
            <div class="col width-4of5">
                <div class="cell">
                    <?php echo $content; ?>
                </div>
            </div>

            <div class="col width-1of5">
                <div class="cell">
                    <?php Layout::renderRegion('right'); ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>