<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
		<div id="left-sidebar">
			<div id="sidebar">
			<?php $this->widget('bootstrap.widgets.BootMenu', array(
			    'type'=>'list',
			    'items'=>$this->menu,
			)); ?>
			</div><!-- sidebar -->
		</div>
		<div id="main-content">
			<div id="content">
				<?php echo $content; ?>
			</div><!-- content -->
		</div>
</div>
<?php $this->endContent(); ?>
