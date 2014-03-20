
<?php
$this->widget("ext.dwz.DwzAccordion", array(
    'panels' => array(
        'panel 1' => 'content for panel 1',
        'panel 2' => 'content for panel 2',
        // panel 3 contains the content rendered by a partial view
    ),
    'htmlOptions'=>array(
       // 'fillSpace'=>"content",
    ),
)); ?>

 <div class="pageFormContent" layoutH="80" style="margin-right:0px">
     yii layout here

                        <?php $this->widget("ext.dwz.DwzAccordion", array(
    'panels' => array(
        'panel 1' => 'content for panel 1',
        'panel 2' => 'content for panel 2',
        // panel 3 contains the content rendered by a partial view
        'panel3' => array('content for panel 3','htmlOptions'=>array('style'=>'height:100px'))

    ),
    'htmlOptions'=>array('')
)); ?>