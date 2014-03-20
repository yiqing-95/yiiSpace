<div class="panel">
   	<b>id:</b>
	<?php echo CHtml::link(CHtml::encode($data['id']),array('view','id'=>$data['id'])); ?>
	<br />

	<b>relation type name:</b>
	<?php echo CHtml::encode($data['type_name']); ?>
	<br />

	<b>relation type plural name:</b>
	<?php echo CHtml::encode($data['type_plural_name']); ?>
	<br />

    <b>user a name :</b>
    <?php echo CHtml::encode($data['user_a_name']); ?>
    <br />

	<b>user b name ; :</b>
	<?php echo CHtml::encode($data['user_b_name']); ?>

    <?php
        echo CHtml::link(CHtml::encode('accept'), 'javascript:void(0);',
            array(
                'class' => 'tiny button',
                'onclick'=>'handleRelation(this,"approve")',
                'relation_id'=>$data['id'],
            ));  ?>
     OR
    <?php
    echo CHtml::link(CHtml::encode('reject'), 'javascript:void(0);',
        array(
            'class' => 'tiny button',
            'onclick'=>'handleRelation(this,"reject")',
            'relation_id'=>$data['id'],
        ));  ?>

</div>