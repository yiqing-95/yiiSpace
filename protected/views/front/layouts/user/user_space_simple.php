<?php $this->beginContent('//layouts/_main'); ?>
<style type="text/css">
    .btn-toolbar .btn-group {
        display: inline-block;
    }

    #page {
        border-radius: 10px 10px 10px 10px;
        background: none repeat scroll 0 0 white;
    }
</style>

<div class="container">
    <div class="container-fluid"  style="margin-top: 20px;">

        <div class="span2" align="center">
            <?php
            $user = new User();
            $profile = UserHelper::getUserPublicProfile();
            $user = $profile->getUserModel();
            $profile->renderMediumUserIcon();
            ?>
        </div>
        <div class="span8">

            <div class="btn-toolbar " align="center">
                <?php
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'size' => 'small', //large,small , mini
                    'htmlOptions' => array(
                        //  'class'=>'pull-right',
                    ),
                    'buttons' => array(
                        array('label' => '日志', 'url' => '#'),
                        // array('label' => '相册', 'url' => array('/album/member','u'=>$_GET['u'])),
                        array('label' => '相册', 'url' => array('/album/member')),
                        array('label' => '微博', 'url' => '#'),
                        array('label' => '收藏', 'url' => '#'),
                        array('label' => '分享', 'url' => '#'),
                    ),
                ));
                $this->widget('bootstrap.widgets.TbButtonGroup', array(
                    'size' => 'mini',
                    'type' => 'info', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                    'buttons' => array(
                        array('label' => 'Inverse', 'items' => array(
                            array('label' => 'Action', 'url' => '#'),
                            array('label' => 'Another action', 'url' => '#'),
                            array('label' => 'Something else', 'url' => '#'),
                            '---',
                            array('label' => 'Separate link', 'url' => '#'),
                        )),
                    ),
                ));
                ?>
            </div>


        </div>


    </div>
    <!--    用户菜单（顶级）end -->

    <?php
    /*$this->widget('bootstrap.widgets.TbNavbar', array(
       'brand'=>false,
       'type'=>'inverse', // null or 'inverse'
       'collapse'=>true, // requires bootstrap-responsive.css
       'fixed'=>false,
       'fluid'=>true ,
       'items'=>array(
           $subNavBar,
       ),
   )); */?>

    <div id="page" class="span12" style="margin-top: 15px;">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid" style="margin: 0px;">
                    <?php if (isset($this->breadcrumbs)): ?>
                        <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                    )); ?><!-- breadcrumbs -->
                    <?php endif?>

                </div>

                <div class="row-fluid">
                    <?php echo $content;?>
                </div>
            </div>
        </div>



    </div>

</div>


<?php $this->endContent(); ?>