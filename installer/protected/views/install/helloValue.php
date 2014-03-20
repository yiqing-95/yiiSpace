<?php
echo $event->sender->menu->run();
echo '<div>Step '.$event->sender->currentStep.' of '.$event->sender->stepCount;
echo '<h3>'.$event->sender->getStepLabel($event->step).'</h3>';
?>

<div>
    <p>
        <h3>
        hello <?php echo Yii::app()->name ; ?>
        </h3>
    </p>
</div>

<?php
echo CHtml::tag('div',array('class'=>'form'),$form);
?>