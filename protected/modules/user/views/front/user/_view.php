<div class="view panel">
   <div class="two columns">
       <?php
       $picId = rand(1,5);
       $userPhotoUrl = empty($data->profile->photo)? PublicAssets::instance()->url("images/user/avatars/5.jpg"): bu($data->profile->photo) ;

       ?>
       <img src="<?Php echo $userPhotoUrl; ?>"
            width="360px" height="360px"
            alt=""/>
   </div>

    <b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->username), array("user/space", "u" => $data->id))  ?>
    <br/>
    <b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
    <?php echo CHtml::encode($data->email); ?>
    <br/>

    <?php
    if(!user()->getIsGuest())
    echo CHtml::link(CHtml::encode('add as friend'), 'javascript:void(0);',
    array(
        'class' => 'tiny button',
        'onclick'=>'addFriend(this)',
        'friend_id'=>$data->id
    ));  ?>

    <?php /*
    <b><?php echo CHtml::encode($data->getAttributeLabel('create_at')); ?>:</b>
    <?php echo CHtml::encode($data->create_at); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('lastvisit_at')); ?>:</b>
    <?php echo CHtml::encode($data->lastvisit_at); ?>
    <br />

    */ ?>

</div>