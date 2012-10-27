<!doctype html>
<html>
<head>

    <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico">
</head>

<body id="top">

<div id="fb-root"></div>


<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'type'=>'inverse',
    'brand'=>CHtml::encode(Yii::app()->name),
    'collapse'=>true,
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Demo', 'url'=>Yii::app()->homeUrl,
                    'active'=>Yii::app()->controller->id === 'site' && Yii::app()->controller->action->id === 'index'),
                array('label'=>'Setup', 'url'=>array('site/setup')),
            ),
            'htmlOptions'=>array('class'=>'pull-left'),
        ),
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Bootstrap Docs', 'url'=>'http://twitter.github.com/bootstrap', 'linkOptions'=>array('target'=>'_blank')),
                array('label'=>'Fork me on Bitbucket', 'url'=>'http://www.bitbucket.org/Crisu83/yii-bootstrap', 'linkOptions'=>array('target'=>'_blank')),
                array('label'=>'Follow me on Twitter', 'url'=>'http://www.twitter.com/Crisu83', 'linkOptions'=>array('target'=>'_blank')),
            ),
            'htmlOptions'=>array('class'=>'pull-right'),
        ),
    ),
)); ?>

<div class="container">

    <?php $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
    'heading'=>Yii::app()->name,
)); ?>
    <p>
        Bringing together the <?php echo CHtml::link('Yii PHP framework', 'http://www.yiiframework.com'); ?> and
        <?php echo CHtml::link('Twitter Bootstrap', 'http://twitter.github.com/bootstrap/'); ?>.
    </p>
    <p>
        <?php echo CHtml::link('Yii-Bootstrap', 'http://www.yiiframework.com/extension/bootstrap/'); ?>
        is an extension for Yii that provides a wide range of widgets that allow developers to easily use Bootstrap with Yii.
        All widgets have been developed following Yii's conventions and work seamlessly together with Bootstrap and its jQuery plugins.
    </p>
    <?php $this->endWidget(); ?>

    <?php if (!empty($this->breadcrumbs)):?>
    <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
    )); ?>
    <?php endif?>

    <?php echo $content; ?>


        <?php

        $this->widget('bootstrap.widgets.TbNavbar', array(
        'fixed'=> false , //'bottom',
        'brand'=>false,
        'collapse'=>true,
        'htmlOptions'=>array('class'=>'subnav'),
        'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbMenu',
                'scrollspy'=>'.navbar',
                'items'=>array(
                    array('label'=>'Buttons', 'url'=>'#tbButton'),
                    array('label'=>'Buttons groups', 'url'=>'#tbButtonGroup'),
                    array('label'=>'Navigation', 'items'=>array(
                        array('label'=>'Breadcrumb', 'url'=>'#tbBreadcrumbs'),
                        array('label'=>'Menu', 'url'=>'#tbMenu'),
                        array('label'=>'Navbar', 'url'=>'#tbNavbar'),
                    )),
                    array('label'=>'Tables', 'items'=>array(
                        array('label'=>'Detail view', 'url'=>'#tbDetailView'),
                        array('label'=>'Grid view', 'url'=>'#tbGridView'),
                    )),
                    array('label'=>'Forms', 'url'=>'#tbActiveForm'),
                    array('label'=>'Hero unit', 'url'=>'#tbHero'),
                    array('label'=>'Thumbnails', 'url'=>'#tbThumbnails'),
                    array('label'=>'Alert', 'url'=>'#tbAlert'),
                    array('label'=>'Progress', 'url'=>'#tbProgress'),
                    array('label'=>'Labels', 'url'=>'#tbLabel'),
                    array('label'=>'Badges', 'url'=>'#tbBadge'),
                    array('label'=>'Javascript plugins', 'items'=>array(
                        array('label'=>'Carousel', 'url'=>'#tbCarousel'),
                        array('label'=>'Modal', 'url'=>'#tbModal'),
                        array('label'=>'Popover', 'url'=>'#tbPopover'),
                        array('label'=>'Tabs', 'url'=>'#tbTabs'),
                        array('label'=>'Tooltip', 'url'=>'#tbTooltip'),
                        array('label'=>'Typeahead', 'url'=>'#tbTypeahead'),
                    )),
                ),
            ),
        ),
    )); ?>



    <hr />

    <footer>

        <p class="powered">
            Powered by <?php echo CHtml::link('Yii PHP framework', 'http://www.yiiframework.com', array('target'=>'_blank')); ?> /

        </p>

        <p class="copy">
            &copy; YiiSpace <?php echo date('Y'); ?>
        </p>

    </footer>

</div>

<?php Yii::app()->clientScript->registerScriptFile('http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f362fc83fc39768', CClientScript::POS_END); ?>

</body>
</html>
