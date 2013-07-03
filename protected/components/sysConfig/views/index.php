<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
    You may change the content of this page by modifying
    the file <tt><?php echo __FILE__; ?></tt>.
</p>

<div>
    <?php if (Yii::app()->user->hasFlash('success')) {
    echo "<h3>".(Yii::app()->user->getFlash('success'))."</h3>" ;
} ?>


</div>
<hr/>
<div class="form">
     <hr/>
    <?php
     $formBegin =   $form->renderBegin() ;
    $formButton = $form->renderButtons() ;
    $formEnd = $form->renderEnd();
    /**
     * @see http://www.yiiframework.com/extension/detailview4col/
     * use this extension to format the form output ! for {label},{input},{hint},{error}
     */
    $attributes = array();
    foreach($form->getElements() as $eleName=>$formElement){

        //echo "{$eleName} => <br/>".$form[$eleName];
        // echo  $form[$eleName] ,'=>', $eleConfig , '<br./>';
       if($formElement instanceof CFormInputElement){
           $formElement->layout = "{input}\n{hint}\n{error}";//"{label}\n{input}\n{hint}\n{error}";;
           //echo $formElement ;
           $attributes[] = array('label'=>$formElement->renderLabel(), 'type'=>'raw','value'=>$formElement);
       }elseif($formElement instanceof CFormStringElement){
           $attributes[] = array('label'=>'', 'type'=>'raw','value'=>$formElement);
       }
     }

    echo $formBegin , "<br/>";
    $this->widget('zii.widgets.CDetailView', array(
        'data'=>$form->getModel(),
        'attributes'=>$attributes,
    ));
    echo $formButton ,"\n", $formEnd ;
    ?>
</div>

