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
<?php require_once(Yii::getPathOfAlias('application.requirements'). DIRECTORY_SEPARATOR . 'YiiRequirementChecker.php');
 $requirementsChecker = new YiiRequirementChecker();
/*
 $requirements = array(
      array(
          'name' => 'PHP Some Extension',
         'mandatory' => true,
         'condition' => extension_loaded('some_extension'),
         'by' => 'Some application feature',
         'memo' => 'PHP extension "some_extension" required',
     ),
 );
*/
$requirements = require( Yii::getPathOfAlias('application.requirements'). DIRECTORY_SEPARATOR . 'requirements.php');
 $requirementsChecker->checkYii()->check($requirements)->render();
?>

<?php
echo CHtml::tag('div',array('class'=>'form'),$form);
?>