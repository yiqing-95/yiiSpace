<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'list',
    'items'=>array(
        array('label'=>'朋友'),
        array('label'=>'Profile', 'icon'=>'user', 'url'=>'#'),
        array('label'=>'我的基友', 'icon'=>'home', 'url'=>array('/friend/relationship/myRelationships'), 'active'=>true),
        array('label'=>'好友请求', 'icon'=>'book', 'url'=>array('/friend/relationship/pendingRelationships')),
        array('label'=>'Application', 'icon'=>'pencil', 'url'=>'#'),
        array('label'=>'消息'),
        array('label'=>'收件箱', 'icon'=>'cog', 'url'=>array('/msg/inbox')),
        array('label'=>'创建消息', 'icon'=>'flag', 'url'=>array('/msg/create')),
        array('label'=>'应用'),
        array('label'=>'相册', 'icon'=>'photo', 'url'=>array('/album/my')),
        array('label'=>'日志', 'icon'=>'flag', 'url'=>array('/blog/my')),

    ),
)); ?>

<?php /*?>
        <ul>
            <li>
                <?php echo CHtml::link('photo', array('/user/settings/photo')); ?>
            </li>
            <li>
                <?php echo CHtml::link('msg Inbox', array('/msg/inbox')); ?>
            </li>
            <li>
                <?php echo CHtml::link('msg create', array('/msg/create')); ?>
            </li>
            <li>
                <?php echo CHtml::link('my connections', array('/friend/relationship/myRelationships')); ?>
            </li>

            <li>
                <?php echo CHtml::link('pending relationship', array('/friend/relationship/pendingRelationships')); ?>
            </li>
            <li>
                <?php echo CHtml::link('my connections', array('/friend/relationship/myRelationships')); ?>
            </li>

        </ul>
        <ul>
            <li>
                <?php echo CHtml::link('updateStatus', array('/status/create')); ?>
            </li>
            <li>
                <?php echo CHtml::link('my stream', array('/status/stream')); ?>
            </li>
        </ul>
        <ul>
            <li>
                <?php echo CHtml::link('myGroup', array('/group/group')); ?>
            </li>
        </ul>
<?php */ ?>