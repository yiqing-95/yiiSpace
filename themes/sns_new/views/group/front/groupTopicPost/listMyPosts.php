<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */

$this->breadcrumbs=array(
	'Group Topic Posts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GroupTopicPost', 'url'=>array('index')),
	array('label'=>'Create GroupTopicPost', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiListView.update('group-topic-post-list', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Group Topic Posts</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button icon-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div  class="">
                    <ul id="ajax-sorter-nav">
                        <?php
                        $sort = $dataProvider->getSort();
                        $sortableAttributes = array(
                            'create_time'=>'创建时间',
                        );
                        foreach($sortableAttributes as $name=>$label)
                        {
                            echo "<li>";
                            echo $sort->link($name,$label);
                            echo "</li>\n";
                        }
                        ?>
                    </ul>
                </div>

                <div class="col">
                    <?php $this->widget('zii.widgets.CListView', array(
                        'id'=>'group-topic-post-list',
                        'dataProvider'=>$dataProvider,
                        'itemView'=>'_myPostView',
                    )); ?>
                </div>

            </div>

        </div>
    </div>
</div>

