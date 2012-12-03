<?php
//$this->layout = false;
$this->widget('my.widgets.rating.JRating', array(
    'selector' => '.basic',
    'options' => array(
        'phpPath' => $this->createUrl(''),
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

    below is CStarRating build in yii framework
    <hr/>
    <?php
    $this->widget('CStarRating', array(
//'model'=>$model,
//'attribute'=>'rating',
        'name' => 'rating1',
        'value' => 3,
    ));
    ?>
</div>
<div class="row">
    <?php
    //because we are activating CSRF and se using POST, we must give token to the AJAX Parameter
    /**
     * @see http://www.yiiframework.com/forum/index.php/topic/24793-cstarrating-remove-vote/page__p__120016__hl__CStarRating#entry120016
     * data: "' . Yii::app()->request->csrfTokenName.'='.Yii::app()->request->getCsrfToken() . '" + (typeof($(this).attr("value")) == "undefined" ? "&cancel=1": "&rate=" + $(this).val())  + "&id=' . $data->id . '",
     */
    $this->widget('CStarRating', array(
        'name' => 'ratingAjax',
        'callback' => '
        function(){
        $.ajax({
        type: "POST",
        url: "' . Yii::app()->createUrl('/sys/starRatingAjax') . '",
        data: "' . Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->getCsrfToken() . '&rate=" + $(this).val(),
        success: function(msg){
        $("#result").html(msg);
        }})}'
    ));
    echo "<br/>";
    echo "<div id='result'>No Result</div>";
    $this->widget('CStarRating', array(
        //  'model'=>$comment,
        // 'attribute'=>'rating',
        'name' => 'hi',
        'maxRating' => 5,
        'titles' => array(1 => 'weak', 2 => 'so so', 3 => 'good', 4 => 'thats great', 5 => 'the best'),
        'allowEmpty' => true, //Setting the allowEmpty property to false will remove the cancel button.
        'htmlOptions' => array(
            'class' => 'rating'
        )
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