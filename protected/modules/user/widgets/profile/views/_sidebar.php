<div id="userSideNav">
<!--    <div style="text-align:center; padding-top: 5px;">-->
<!--        <img src="uploads/profile/{profile_photo}" />-->
<!--    </div>-->
    <div style="padding: 5px;">
        <h2>Friends</h2>
        <ul>
            <!-- START profile_friends_sample -->
            <li>
                <?php echo CHtml::link(CHtml::encode($model->username),array('/user/user/space','u'=>$model->id)); ?>
            </li>
            <!-- END profile_friends_sample -->
            <li>
                <?php echo CHtml::link(CHtml::encode('view all relations'),array('/friend/relationship/viewAll','u'=>$model->id)); ?>
               </li>
            <li>
                <a href="relationships/mutual/{profile_user_id}">View
                mutual friends</a>
            </li>
        </ul>
        <h2>Rest of my profile</h2>
        <ul>
            <li><a href="profile/statuses/{ID}">Status updates</a></li>
            <?php echo CHtml::link('updateStatus',array('/status/create','u'=>$model->id)); ?>
        </ul>
        <h2>action feed</h2>
        <ul>
            <?php echo CHtml::link('allFeedStream',array('/status/create','u'=>$model->id)); ?>
        </ul>
    </div>
</div>