<?php
foreach ($users as $user): ?>

<div class="row-fluid" style="margin: 5px ;">
    <div class="span3 thumbnail">
        <img src="<?Php echo $user->getIconUrl(); ?>"
             width="360px" height="360px"
             alt=""/>

    </div>
    <div class="span8">
        <?php echo CHtml::link(CHtml::encode($user->username), array("/user/user/space", "u" => $user->id),array(
        'target'=>'_blank'
    ))  ?>
        <br/>
    </div>
</div>

<?php endforeach; ?>