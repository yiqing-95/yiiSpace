<?php $this->pageTitle = Yii::app()->name;
 $this->layout='//layouts/found_main';
?>
<script type="text/javascript">
    $(window).load(function () {
        $('#site_banner').orbit();
    });
</script>



<section class="one columns">

</section>
<section class="ten columns ">
    <div id="site_banner">
        <?php $publicBaseUrl = PublicAssets::url(); ?>
        <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo1.jpg"/>
        <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo2.jpg"/>
        <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo3.jpg"/>
    </div>

    <p>Congratulations! You have successfully created your Yii application.</p>

    <p>You may change the content of this page by modifying the following two files:</p>
    <ul>
        <li>View file: <tt><?php echo __FILE__; ?></tt></li>
        <li>Layout file: <tt><?php echo $this->getLayoutFile('main'); ?></tt></li>
    </ul>

    <p>For more details on how to further develop this application, please read
        the <a href="http://www.yiiframework.com/doc/">documentation</a>.
        Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
        should you have any questions.</p>

    <H2>what's happening </H2>
    <div id="activityStreams">
        <?php
          //WebUtil::ajaxLoad('#activityStreams',array('/user/actionFeed'));
        //$this->widget('ext.flowing-calendar.FlowingCalendarWidget', array("month" => intval(date('m')), "year" => intval(date('Y'))));
        ?>
    </div>

</section>
<section class="one columns">

</section>