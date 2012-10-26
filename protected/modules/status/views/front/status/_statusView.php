<div class="panel">
    <b> id:</b>
    <?php echo CHtml::link(CHtml::encode($data['id']), array('view', 'id' => $data['id'])); ?>
    <br/>
    <b>type_reference:</b>
    <?php echo CHtml::encode($data['type_reference']); ?>
    <br/>
    <b>type_name:</b>
    <?php echo CHtml::encode($data['type_name']); ?>
    <br/>
    <b>id:</b>
    <?php echo CHtml::encode($data['id']); ?>
    <br/>
    <b>update:</b>
    <?php echo CHtml::encode($data['update']); ?>
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