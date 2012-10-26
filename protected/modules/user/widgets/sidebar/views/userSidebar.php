<?php
$picId = rand(1, 5);
$userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg") : bu($profile->photo);

?>

<div class="user-sidebar two columns">
    <table class="dataGrid">
        <tr>
            <th colspan="2">
                <div>
                    <img src="<?Php echo $userPhotoUrl; ?>"
                         width="120px" height="120px"
                         alt=""/>
                </div>
            </th>
        </tr>
        <tr>
            <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
            <td><?php echo CHtml::encode($model->username); ?></td>
        </tr>
        <?php
        $profileFields = ProfileField::model()->forOwner()->sort()->findAll();
        if ($profileFields) {
            foreach ($profileFields as $field) {
                //echo "<pre>"; print_r($profile); die();
                ?>
                <tr>
                    <th class="label"><?php echo CHtml::encode(UserModule::t($field->title)); ?></th>
                    <td><?php echo (($field->widgetView($profile)) ? $field->widgetView($profile) : CHtml::encode((($field->range) ? Profile::range($field->range, $profile->getAttribute($field->varname)) : $profile->getAttribute($field->varname)))); ?></td>
                </tr>
                <?php
            }
            //$profile->getAttribute($field->varname)
        }
        ?>
        <tr>
            <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></th>
            <td><?php echo CHtml::encode($model->email); ?></td>
        </tr>
        <tr>
            <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></th>
            <td><?php echo $model->create_at; ?></td>
        </tr>
        <tr>
            <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?></th>
            <td><?php echo $model->lastvisit_at; ?></td>
        </tr>
        <tr>
            <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></th>
            <td><?php echo CHtml::encode(User::itemAlias("UserStatus", $model->status)); ?></td>
        </tr>
    </table>

    <section class="sideNav ">
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
    </section>
</div>