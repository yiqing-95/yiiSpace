<?php $objectName = 'photo';
   $objectId = 3;
?>

<div id="photo_comments">
    <script type="text/javascript">
        $(function(){
            $("#photo_comments").load("<?php echo  $this->createUrl('/sys/comment',array('objectName'=>$objectName,'objectId'=>$objectId));?>")
        });
    </script>
</div>