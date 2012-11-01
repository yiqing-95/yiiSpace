<?php
foreach ($models as $data): ?>

<div class="row-fluid" style="margin: 5px ;">
    <div class="span3 thumbnail">
        <a href="<?php echo Yii::app()->createUrl("/user/user/space", array("u" => $data->owner->id)); ?>">
        <img src="<?Php echo bu($data->owner->profile->photo); ?>"
             width="360px" height="360px"
             alt=""/>
        </a>
    </div>
    <div class="span8">
        <?php echo CHtml::link(CHtml::encode($data->owner->username), array("/user/user/space", "u" => $data->owner->id),array(
        'target'=>'_blank'
    ))  ?>
        <br/>

    </div>
</div>

<?php endforeach; ?>