<?php
$this->breadcrumbs = array(
    'Posts',
);

$this->menu = array(
    array('label' => 'Create Post', 'url' => array('create')),
    array('label' => 'Manage Post', 'url' => array('admin')),
);
?>

<?php
$this->widget('blog.widgets.pagebox.BlogSliderPageBox');
?>

    <h1>Posts</h1>


<?php foreach($blogSysCategories as $blogSysCate): ?>

    <?php $this->widget('blog.widgets.SysCategoryPostsPageBox',array(
        'sysCategory'=>$blogSysCate,
    )); ?>

<?php endforeach; ?>


<?php Layout::beginBlock('rightSideBar'); ?>

<?php YsPageBox::beginPanelWithHeader(array('header' => ' 标签云')) ?>
    <div class="cell">
        <?php // $this->widget('UserMenu'); ?>

        <?php
        /*
        $this->widget('TagCloud', array(
            'maxTags' => 15,
        ));
        */
        ?>

        <?php

        $arrTags = array();
        $tags=Tag::model()->findTagWeights(18);

        foreach($tags as $tag=>$weight)
        {
            $arrTags[$tag] = array(
                'weight'=>$weight,
                'url'=>$this->createUrl('post/list',array('tag'=>str_replace(' ','-',  trim($tag)))),
                 'htmlOptions'=>array(
                     'target'=>'_self',
                 )
            );
        }

        $this->widget('application.extensions.YiiTagCloud.YiiTagCloud',
            array(
                'beginColor' => '00089A',
                'endColor' => 'A3AEFF',
                'minFontSize' => 8,
                'maxFontSize' => 20,
                'arrTags' => $arrTags,
            )
        );
        ?>

    </div>

<?php YsPageBox::endPanel(); ?>


<?php Layout::endBlock(); ?>