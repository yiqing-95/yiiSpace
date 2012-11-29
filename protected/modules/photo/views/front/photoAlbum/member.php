<?php
$this->breadcrumbs = array(
    'Photo Albums',
);

$this->menu = array(
    array('label' => 'Create PhotoAlbum', 'url' => array('create')),
    array('label' => 'Manage PhotoAlbum', 'url' => array('admin')),
);
?>
<?php
if (UserHelper::getIsLoginUser() && UserHelper::getIsOwnSpace()) {
    $this->beginClip('user_actions');
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons' => array(
            array('label' => '上传照片', 'url' =>array(PhotoHelper::getUploadPhotoRoute())),
            array('label' => '创建相册', 'url' => array(PhotoHelper::getCreateAlbumRoute()), 'htmlOptions' => array('class' => 'create')),
        ),
    ));

    $this->widget('application.extensions.formDialog2.FormDialog2', array(
            'link' => 'a.create',
            'options' => array(
                'onSuccess' => 'js:function(data, e){alert(data.message);
                       refreshAlbumList();
                }',
            ),
            'dialogOptions' => array(
                'title'=>'创建相册',
                'width' => 600,
                'height' => 470,

            )
        )
    );
    $this->widget('application.extensions.formDialog2.FormDialog2', array(
            'link' => 'a.update',
            'options' => array(
                'onSuccess' => 'js:function(data, e){alert(data.message);
                       refreshAlbumList();
                }',
            ),
            'dialogOptions' => array(
                'title'=>'编辑相册',
                'width' => 600,
                'height' => 470,

            )
        )
    );
    $this->widget('application.my.widgets.EAjaxDelete', array(
            'link' => 'a.delete',
            'listViewId'=>'album_list',
            'deleteConfirmation'=>'你确定要删除该相册么',
        )
    );

    $this->endClip();
    $userActions = $this->clips['user_actions'];
} elseif (UserHelper::getIsLoginUser()) {
    $userActions = $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons' => array(
            array('label' => 'Left', 'url' => '#'),
            array('label' => 'Middel', 'url' => '#'),
            array('label' => '我的相册', 'url' => array(PhotoHelper::getMyAlbumRoute(), 'u' => UserHelper::getVisitorId())),
        ),
    ), true);
} else {
    $userActions = $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons' => array(
            array('label' => '注册吧骚年', 'url' => '#'),
            array('label' => '登陆', 'url' => '#'),
        ),
    ), true);
}
?>

<?php
$this->widget('bootstrap.widgets.TbNavbar', array(
//'brand' => 'Title',
    'fixed' => false,
    'items' => array(
        "<form class=\"navbar-form pull-right\">
            {$userActions}
        </form>"
    )
));


?>

<h1>Photo Albums</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
    'id'=>'album_list',
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>

<script type="text/javascript">
    function refreshAlbumList(){
        $.fn.yiiListView.update('album_list');
    }
</script>