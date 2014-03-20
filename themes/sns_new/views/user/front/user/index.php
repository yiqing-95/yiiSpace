<?php
$this->breadcrumbs = array(
    UserModule::t("Users"),
);
if (UserModule::isAdmin()) {

    $this->menu = array(
        array('label' => UserModule::t('Manage Users'), 'url' => array('/user/admin')),
        array('label' => UserModule::t('Manage Profile Field'), 'url' => array('profileField/admin')),
    );
}
?>

<?php
Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
        });
        $('.search-form form').submit(function(){
        $.fn.yiiListView.update('user_list', {
             data: $(this).serialize()
         });
    return false;
    });
");
?>

<?php $this->beginClip('searchForm'); ?>
<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button btn')); ?>
<div class="search-form" style="display:block;">
    <?php $this->renderPartial('/user/_search', array(
    'model' => $model,
)); ?>
</div><!-- search-form -->
<?php $this->endClip(); ?>

<?php $this->widget('wij.WijTabs', array(
    'theme' => EWijmoWidget::THEME_ARISTO,
    'htmlOptions' => array(
        'class' => 'controls',
    ),
    'tabs' => array(
        'search' => array('content' => $this->clips['searchForm'], 'active' => true),
        'quickLinks' => array('content' => '<p>all quick links for searching the different status (such as :active ,deleted,...)</p>'),
        //'Tags'=> array('content'=>'<p>search with tags , here you prepare the available tags</p>'),
    ),
)); ?>

<h1><?php echo UserModule::t("List User"); ?></h1>

<?php
/*
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
		),
		'create_at',
		'lastvisit_at',
	),
)); */

$this->widget('zii.widgets.CListView', array(
    'id' => 'user_list',
    'dataProvider' => $dataProvider,
    'itemView' => '/user/_view',
));

?>
<?php  $this->widget('wij.WijDialog', array(
    'selector' => '#dialog-modal',
    'theme' => EWijmoWidget::THEME_COBALT,
    'options' => 'js:
    {
                autoOpen: false,
                height: 200,
                width: 300,
                modal: true
            }'
));
$this->widget('wij.WijDialog', array(
    'selector' => '#dialog-message',
    'theme' => EWijmoWidget::THEME_COBALT,
    'options' => 'js:
    {
                        autoOpen: false,
                        height: 180,
                        width: 400,
                        modal: true,
                        buttons: {
                            Ok: function () {
                                $(this).wijdialog("close");
                            }
                        },
                        captionButtons: {
                            pin: { visible: false },
                            refresh: { visible: false },
                            toggle: { visible: false },
                            minimize: { visible: false },
                            maximize: { visible: false }
                        }
                    }'
));

/*
$this->widget('my.widgets.jbox.JBox', array(
    'skinBase' => 'Skins2',
    'skin' => 'Pink',
    'debug' => false,
));
*/
?>
<div id="dialog-modal" title="add as friend">
    <div id="add_friend_form">
        loading.........
    </div>
</div>
<div id="dialog-message" title="congrats !">
    <p>
        <span class="ui-icon ui-icon-circle-check"></span>
        relation has created successfully!
    </p>
</div>

<script type="text/javascript">
    function addFriend(linkEle) {
        if ($("form", "#add_friend_form").size() < 1) {
            var url = "<?php echo $this->createUrl('/friend/relationship/create'); ?>";
            var params = {
                "user_b":$(linkEle).attr("friend_id")
            };
            $.post(url, params, function (resp) {
                $("#add_friend_form").html(resp.div);
                $('#dialog-modal').wijdialog('open');
            }, "json");
        } else {

            $("input[name*='user_b']","#add_friend_form").val($(linkEle).attr("friend_id"));
            // just set the user_b ,don't need a ajax request again!
            $('#dialog-modal').wijdialog('open');
        }
        /* $.jBox('id:dialog-modal', { bottomText: 'bottomText' });*/
    }

    function submitForm() {
        var $submitBtn = $(this);
        var $form = $("form", '.form');
        var url = $form.attr("action");
        var data = $form.serialize();
        $.ajax({
            type:"POST",
            url:url,
            data:data,
            dataType:"json",
            success:function (data) {
                if (data.success == false) {
                    var $formParent = $form.parent();
                    $formParent.html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $("form", $formParent).submit(submitForm);
                } else {
                    var $formParent = $form.parent();
                    $formParent.html(data.div);
                    // Here is the trick: on submit-> once again this function!
                    $('form', $formParent).submit(submitForm);
                    $("#dialog-message").wijdialog('open');
                }
            }
        });
        return false;
    }

</script>



