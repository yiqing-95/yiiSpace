<?php
/* @var $this GroupTopicController */
/* @var $model GroupTopic */

$this->breadcrumbs=array(
	'Group Topics'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GroupTopic', 'url'=>array('index')),
	array('label'=>'Create GroupTopic', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiListView.update('group-topic-list', {
		data: $(this).serialize()
	}
	);
	return false;
});
");
?>

<h1>Manage Group Topics</h1>

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
                            'created'=>'创建时间',
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

                    <?php
                   // $dataProvider = $model->search() ;

                    $this->widget('zii.widgets.CListView', array(
                        'id'=>'group-topic-list',
                        'dataProvider'=>$dataProvider,
                        'itemView'=>'_view',
                    )); ?>

                </div>

            </div>

        </div>
    </div>
</div>

