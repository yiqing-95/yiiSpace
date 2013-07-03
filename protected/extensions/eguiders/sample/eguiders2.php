<?php

$nextLink = Yii::app()->createUrl('extension/eguiders',array('#' => 'guider=page1_step3'));

$this->widget('ext.eguiders.EGuider', array(
		'id'			=> 'page2_step2',
		'next' 			=> 'position',
		'title'			=> 'Yes... We reach the page !',
		'buttons' 		=> array(
			array('name'=>'Next', 'onclick'=> "js:function(){document.location = '$nextLink';}"),
		),
		'description' 	=> 'As you can see, the guided tour doesn\'t have to be limited to a single page : jump jump jump ... to any page !',
		'xButton'		=> true,
		'isHashable'    => true,
		'autoFocus'	    => true
	)
);
?>
<div class="row">
	<div class="span5 offset2">
		<img src="images/eguiders.png" alt="logo"/>
	</div>
	<div class="span5">
		<h2>... let's continue the tour !!</h2>
	</div>
</div>