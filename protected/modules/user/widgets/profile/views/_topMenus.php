<div class="well well-small btn-toolbar " align="center">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'size' => 'large',
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
        'size' => 'large',
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