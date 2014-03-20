
        <?php if(isset($data->recommendGrade)): ?>
            <?php  echo WebUtil::ajaxDeleteLink('delete',array('blogRecommend/delete','id'=>$data->recommendId),
                     array('success'=>'js:function(data){
                         reloadItemsView("body");
                 }'),
                array('class'=>'recommend btn btn-mini')
            );
            ?>
            <?php echo CHtml::link(CHtml::encode('修改推荐'),array('blogRecommend/update','id'=>$data->recommendId ) ,
                array('class'=>'recommend btn btn-mini'));
            ?>
            <?php else:?>

            <?php echo CHtml::link(CHtml::encode('推荐该博文'),array('blogRecommend/create','objectId'=>$data->id ) ,
                array('class'=>'recommend btn btn-mini'));
            ?>
        <?php endif ;?>
