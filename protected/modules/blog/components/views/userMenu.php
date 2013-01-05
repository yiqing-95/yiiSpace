<ul>
	<?php if(Yii::app()->user->isGuest): ?>
    
    <li>
        <?php $this->widget('ext.oauthLogin.OauthLogin',array(
            'itemView'=>'medium_login',
            'back_url'=>Yii::app()->homeUrl,
         )); ?>
        <?php echo CHtml::link('Login',array('site/login')); ?></li>
    <?php endif; ?>
	<?php if(!Yii::app()->user->isGuest): ?>
    <li><?php echo CHtml::link('Create New Post',array('post/create')); ?></li>
	<li><?php echo CHtml::link('Manage Posts',array('post/admin')); ?></li>
	<li><?php echo CHtml::link('Manage Backend',Yii::app()->createUrl('dlf-admin.php')); ?></li>
	<li><?php echo CHtml::link('Approve Comments',array('comment/index')) . ' (' . Comment::model()->pendingCommentCount . ')'; ?></li>
	<li><?php if(!Yii::app()->user->isGuest) echo CHtml::link(Yii::app()->user->name.'(Logout)',array('site/logout')); ?></li>
    <?php endif; ?>
</ul>