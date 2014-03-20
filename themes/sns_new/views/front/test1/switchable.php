<?php
$this->widget('my.widgets.switchable.JSwitchable', array(

));
$publicBaseUrl = PublicAssets::url();
?>
<script type="text/javascript">
    $(function () {
        $('.slide-trigger').switchable('.slide-panel > div > img', {
            effect:'scroll',
            panelSwitch:true,
            circular:true
        });
    });
</script>

<div class="wrap clearfix">
    <h2>Without triggers</h2>

    <h3>Click image to view the next one</h3>

    <div class="slide-panel" title="Click image to view the next one">
        <div>
            <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo1.jpg"/>
            <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo2.jpg"/>
            <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo3.jpg"/>
            <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo1.jpg"/>
            <img src="<?php echo $publicBaseUrl; ?>/images/orbit-demo/demo2.jpg"/>
        </div>
    </div>
    <div class="slide-trigger"><!-- 标签必须存在，通过CSS隐藏 --></div>
</div>
<?php
$this->widget('bootstrap.widgets.FounNavbar', array(
        'items' => array(
            array('label' => '首页', 'url' => '#', 'active' => true),
            array('label' => '找人', 'url' => array('/user/userSearch')),
            array('label' => '商家', 'url' => array('//merchant/search')),

             array('label' => '应用', 'url' => '#', 'flyout'=>true, 'items' => array(
                array('label' => 'NAV HEADER'),
                array('label' => 'One more separated link', 'url' => '#'),
            )
             ),
        ),

    )
);
?>
