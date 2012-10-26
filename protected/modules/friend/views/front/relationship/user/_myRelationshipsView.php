<div class="panel">
    <?php echo  $data['plural_name'] ; ?>  with
    <?php echo CHtml::link(CHtml::encode($data['users_name']),array('/user/user/space','u'=>$data['id'])); ?>
</div>