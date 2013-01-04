<?php $this->beginContent('/layouts/main'); ?>
<!-- 内容部份开始 --> 
<div id="container">
   <div id="content">
       <?php echo $content; ?>
   </div> 

    <!-- 边栏开始 --> 
   <div id="sidebar">
        <!-- 搜索开始 --> 
       <div class="search">
           <form action="/" method="get" id="searchform" name="keyword" >
           <input type="search"  name="keywords" />
           <input type="submit" value="" name="" class="so" />
           </form>
       </div>
       <!-- 搜索结束 --> 
    <div class="right-ad category">
     ads
    </div>
    <?php $this->widget('UserMenu',array(
        'htmlOptions'=>array('class'=>'category'),
    )); ?>

    <?php $this->widget('TagCloud', array(
        'maxTags'=>Yii::app()->params['tagCloudCount'],
        'htmlOptions'=>array('class'=>'category'),
    )); ?>

    <?php $this->widget('RecentComments', array(
        'maxComments'=>Yii::app()->params['recentCommentCount'],
        'htmlOptions'=>array('class'=>'category'),
    )); ?>

    <?php $this->widget('MonthlyArchives', array(
        'year'=>'年',
        'month'=>'月',
        'htmlOptions'=>array('class'=>'category'),
    )); ?>   
   <!-- 边栏结束 --> 
   <div class="clear"></div>
    </div>
</div>
<!-- 内容部份结束 --> 
            
<?php $this->endContent(); ?>