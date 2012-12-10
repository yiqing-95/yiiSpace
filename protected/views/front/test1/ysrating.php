
<div class="row">
    <?php
    //because we are activating CSRF and se using POST, we must give token to the AJAX Parameter
    /**
     * @see http://www.yiiframework.com/forum/index.php/topic/24793-cstarrating-remove-vote/page__p__120016__hl__CStarRating#entry120016
     * data: "' . Yii::app()->request->csrfTokenName.'='.Yii::app()->request->getCsrfToken() . '" + (typeof($(this).attr("value")) == "undefined" ? "&cancel=1": "&rate=" + $(this).val())  + "&id=' . $data->id . '",
     */
    $this->widget('application.components.sysVoting.YsStarRating', array(
        'name' => 'ratingAjax',
        'objectName'=>'helel',
        'objectId' => 2
    ));
    echo "<br/>";
    echo "<div id='result'>No Result</div>";
  ?>
    <input name="objectName" id="objectName" type="text"/>
    <input name="objectId" id="objectId" type="text"/>

    <?php
    //because we are activating CSRF and se using POST, we must give token to the AJAX Parameter
    /**
     * @see http://www.yiiframework.com/forum/index.php/topic/24793-cstarrating-remove-vote/page__p__120016__hl__CStarRating#entry120016
     * data: "' . Yii::app()->request->csrfTokenName.'='.Yii::app()->request->getCsrfToken() . '" + (typeof($(this).attr("value")) == "undefined" ? "&cancel=1": "&rate=" + $(this).val())  + "&id=' . $data->id . '",
     */
    $this->widget('application.components.sysVoting.YsStarRating', array(
        'name' => 'ratingAjaxDynamicTarget',
        'objectName'=>'js:$("#objectName").val()',
        'objectId' => new CJavaScriptExpression('$("#objectId").val()'),
    ));
    echo "<br/>";
    echo "<div id='result'>No Result</div>";
    ?>
</div>

<script type="text/javascript">

</script>