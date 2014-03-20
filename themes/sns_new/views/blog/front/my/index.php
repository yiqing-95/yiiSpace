<?php
$this->breadcrumbs=array(
	'Posts',
);

$this->menu=array(
	array('label'=>'Create Post', 'url'=>array('create')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);
?>

<?php Layout::beginBlock('rightSideBar'); ?>

<?php YsPageBox::beginPanelWithHeader(array('header'=>'博客分类')) ?>
<div class="cell">
    <div class="col">
        <div class="menu cell">
            <ul class="left links nav">
                <li class="">
                    <a href="<?php echo $this->createUrl('/blog/category/admin'); ?>" class="">分类管理</a>
                </li>
            </ul>
        </div>
        <div class="cell menu">
            <script type="text/javascript">
                $(function(){
                    var url = "<?php echo $this->createUrl('category/ajaxMyCategories'); ?>";
                   $("#myBlogCategories").load(url);
                });
            </script>
            <ul class="stat left nav" id="myBlogCategories">
                <li class=""><a href="#" class=""><span class="data">1,234</span>donuts</a></li>
                <li class=""><a href="#" class=""><span class="data">567</span>kayaks</a></li>
                <li><a href="#"><span class="data">23,456</span>kittens</a></li>
            </ul>
        </div>
    </div>
</div>
<?php YsPageBox::endPanel(); ?>


<?php Layout::endBlock(); ?>
<h1>Posts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	//'ajaxUpdate'=>false,
    'afterAjaxUpdate'=>new CJavaScriptExpression('document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();'),
)); ?>


