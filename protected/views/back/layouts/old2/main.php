<!doctype html>
<html>
<head>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body id="top">

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
    'type' => 'inverse',
    'brand' => CHtml::encode(Yii::app()->name),
    'brandUrl' => false,
    'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array('label' => 'Docs', 'url' => Yii::app()->homeUrl,
                    'active' => Yii::app()->controller->id === 'site' && Yii::app()->controller->action->id === 'index'),
                array('label' => 'Setup', 'url' => array('site/setup')),
            ),
            'htmlOptions' => array('class' => 'pull-left'),
        ),
        '<div class="add-this pull-right">
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style">
                        <a class="addthis_button_facebook"></a>
                        <a class="addthis_button_twitter"></a>
                        <a class="addthis_button_google"></a>
                        <a class="addthis_button_email"></a>
                        <a class="addthis_button_compact"></a>
                        <a class="addthis_counter addthis_bubble_style"></a>
                        </div>
                        <!-- AddThis Button END -->
                </div>',

    ),
)); ?>
<div class="" style="margin-top: 50px;"></div>
<div class="fluid-container">

    <?php echo $content; ?>

    <hr/>

    <footer>

        <div class="row">

            <div class="span6">

                <p class="powered">
                    Powered
                    by <?php echo CHtml::link('Yii', 'http://www.yiiframework.com', array('target' => '_blank')); ?> /
                    <?php echo CHtml::link('Yii-Bootstrap', 'http://www.yiiframework.com/extension/bootstrap', array('target' => '_blank')); ?>
                    /
                    <?php echo CHtml::link('Yii-SEO', 'http://www.yiiframework.com/extension/seo', array('target' => '_blank')); ?>
                    /
                    <?php echo CHtml::link('Bootstrap', 'http://twitter.github.com/bootstrap', array('target' => '_blank')); ?>
                    /
                    <?php echo CHtml::link('jQuery', 'http://www.jquery.com', array('target' => '_blank')); ?> /
                    <?php echo CHtml::link('LESS', 'http://www.lesscss.org', array('target' => '_blank')); ?>
                </p>

            </div>

            <div class="span6">

                <p class="copy">
                    &copy; <?php echo Yii::app()->name ;?> <?php echo date('Y'); ?>
                </p>

            </div>

        </div>

    </footer>

</div>


</body>
</html>