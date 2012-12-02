
<?php
//$this->layout = false;
$this->widget('my.widgets.rating.JRating', array(
     'selector'=>'.basic',
    'options'=>array(
        'phpPath'=>$this->createUrl(''),
    )
));?>
<div class="row">
    <!-- basic exemple -->
    <div class="exemple ">
        <!-- in this exemple, 12 is the average and 1 is the id of the line to update in DB -->
        <div class="basic" id="12_1"></div>
        <!-- in this other exemple, 8 is the average and 2 is the id of the line to update in DB -->
        <div class="basic" id="8_2"></div>
    </div>

</div>
    <hr/>
<div class="row">

    below is CStarRating  build  in yii framework
    <hr/>
    <?php
    $this->widget('CStarRating',array(
//'model'=>$model,
//'attribute'=>'rating',
        'name'=>'rating1',
        'value'=>3,
    ));
    ?>
</div>

<script type="text/javascript">
   /*
    $(document).ready(function(){
        // simple jRating call
        $(".basic").jRating();
        // more complex jRating call
        $(".basic").jRating({
            step:true,
            length : 6, // nb of stars
            onSuccess : function(){
                alert('Success : your rate has been saved :)');
            }
        });
    });*/
</script>