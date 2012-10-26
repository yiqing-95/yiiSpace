<div class="view">
	<b>msg id:</b>
	<?php echo CHtml::link(CHtml::encode($data['id']),array('view','id'=>$data['id'])); ?>
	<br />

	<b>sndr name:</b>
	<?php echo CHtml::encode($data['sender_name']); ?>
	<br />

	<b>read_style:</b>
	<?php echo CHtml::encode($data['read_style']); ?>
	<br />

    <b>subject:</b>
    <?php echo CHtml::encode($data['subject']); ?>
    <br />

    <b>sender:</b>
    <?php echo CHtml::encode($data['sender']); ?>
    <br />

    <b>recipient:</b>
    <?php echo CHtml::encode($data['recipient']); ?>
    <br />

    <b>sent_friendly:</b>
    <?php echo CHtml::encode($data['sent_friendly']); ?>
    <br />
</div>