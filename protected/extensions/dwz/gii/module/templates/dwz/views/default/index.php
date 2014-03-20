<?php echo "
<div>
	<div class=\"accountInfo\">
		<br/>这里是一些测试实例，DwzGridView、DwzGrid参考类文件中说明
		<p>
		要自定义本页请编辑： <tt><?php echo __FILE__; ?></tt>
		</p>
	</div>
	<div class=\"pageFormContent\" layoutH=\"80\">
		<?php echo CHtml::link('测试navTabTodo', array('/admin/default/test'), array('target' => 'navTabTodo', 'title' => 'message')); ?>
		<?php echo CHtml::link('弹出窗中打开', array('/admin/default/test'), array('target' => 'dialog', 'rel' => '窗口标识', 'title' => '[自定义标题]', 'width' => 800, 'height' => 600)); ?>
		<?php echo CHtml::link('在navTab中打开', array('/admin/default/test'), array('target' => 'navTab', 'rel' => 'tab标识号,相同标识号会覆盖之前的')) ?>
		<?php echo CHtml::textField('name', '', array('alt' => '测试input alt扩展')); ?>
		<input type=\"text\" name=\"xxx\" class=\"date\"/>
		<?php echo CHtml::textField('name', '', array('class' => 'date')); ?>
	<?php \$this->widget('ext.dwz.DwzTabs', array(
		'tabs'=>array(
			'标题1'=>'Html<br/>内容',
			'标题2'=> \$this->renderPartial('test',array(),true),
			'ajaxTab'=>array('ajax'=>array('/admin/default/test','id'=>11)),
		),
		'height'=>100,
	)); ?>
	<?php \$this->widget('ext.dwz.DwzPanel',array(
		'title'=>'Panel标题',
		'content'=>'Panel内容',
		'height'=>100,
	)); ?>

	</div>
</div>
	";