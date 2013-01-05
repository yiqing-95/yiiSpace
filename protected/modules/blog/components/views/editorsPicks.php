<ul>
	<?php foreach ($this->getEditorsPicks() as $picks) : ?>
	<li>
	<h4><?php echo CHtml::link(CHtml::encode($picks->title), $picks->url); ?></h4>
	<?php
		$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
		echo $picks->summary;
		$this->endWidget();
	?>
	<div class="hr"></div>
	</li>
	<?php endforeach; ?>
</ul>

