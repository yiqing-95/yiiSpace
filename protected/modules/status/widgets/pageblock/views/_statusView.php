

<div class="row-fluid" style="margin: 5px ; border-top: 2px solid #ffd041 ;">
    <div class="span2 thumbnail" style="margin:4px; ">
        <a href="<?php echo Yii::app()->createUrl("/user/user/space", array("u" =>$data['creator'])); ?>">
            <img src="<?Php echo bu($data['avatar']); ?>"
                 width="360px" height="360px"
                 alt="" class="img-circle" />
        </a>
    </div>
    <div class="span8">
        <?php echo CHtml::link(CHtml::encode($data['username']), array("/user/user/space", "u" => $data['creator']),array(
        'target'=>'_blank'
    ))  ?>
        <br/>
        <b>update:</b>
        <?php echo CHtml::encode($data['update']); ?>
        <hr/>
        <?php  StatusManager::processTypeStatus($data); ?>
        <br>
        <b> <?php echo WebUtil::timeAgo2(strtotime($data['created'])); ?></b>
    </div>
</div>
    <?php /*
<div class="panel">
<br/>

    <br/>
    <b>type:</b>
    <?php echo CHtml::encode($data['type']); ?>
    <br/>
    <b>creator:</b>
    <?php echo CHtml::encode($data['creator']), WebUtil::timeAgo2(strtotime($data['created'])); ?>
    <br/>
    <b>created:</b>
    <?php echo CHtml::encode($data['created']); ?>
    <br/>
    <b>profile:</b>
    <?php echo CHtml::encode($data['profile']); ?>
    <br/>
    <b>approved:</b>
    <?php echo CHtml::encode($data['approved']); ?>
    <br/>
    <b>poster_name:</b>
    <?php echo CHtml::encode($data['poster_name']); ?>
    <br/>
    <b>image:</b>
    <?php echo CHtml::encode($data['image']); ?>
    <br/>
    <b>video_id:</b>
    <?php echo CHtml::encode($data['video_id']); ?>
    <br/>
    <b>url:</b>
    <?php echo CHtml::encode($data['url']); ?>
    <br/>
    <b>description:</b>
    <?php echo CHtml::encode($data['description']); ?>
    <br/>
<div>
<?php  StatusManager::processTypeStatus($data); ?>
</div>
</div>
 */?>