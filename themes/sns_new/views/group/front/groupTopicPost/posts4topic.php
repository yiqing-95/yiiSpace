
<?php
cs()->registerScriptFile(bu('public/js/ys_base.js'));

/* @var $this GroupTopicPostController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Group Topic Posts',
);

$this->menu=array(
	array('label'=>'Create GroupTopicPost', 'url'=>array('create')),
	array('label'=>'Manage GroupTopicPost', 'url'=>array('admin')),
);
?>

<h1>Group Topic Posts</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'id'=>'group-topic-post-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<div class="post-form">
    <?php $this->renderPartial('_form',array(
        'model'=>$model,
    )); ?>
</div><!-- post-form -->