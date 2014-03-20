<?php
 //$this->beginContent('//layouts/main'); //the site main layout
 $this->beginContent('/layouts/main'); //the module theme main layout
?>
<div class="container clear">
	<div id="content">
		<?php  echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>