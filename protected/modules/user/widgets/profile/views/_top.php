<div class="user-space-profile row">
    <section class="two columns avatar" >
        <?php
        $picId = rand(1,5);
        $userPhotoUrl = empty($profile->photo)? PublicAssets::instance()->url("images/user/avatars/5.jpg"): bu($profile->photo) ;

        ?>
        <img src="<?Php echo $userPhotoUrl; ?>"
             width="360px" height="360px"
             alt=""/>
    </section>
    <section>
        <table class="dataGrid">
            <tr>
                <th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></th>
                <td><?php echo CHtml::encode($model->username); ?></td>
            </tr>
            <?php
            $profileFields=ProfileField::model()->forOwner()->sort()->findAll();
            if ($profileFields) {
                foreach($profileFields as $field) {
                    //echo "<pre>"; print_r($profile); die();
                    ?>
                    <tr>
                        <th class="label"><?php echo CHtml::encode(UserModule::t($field->title)); ?></th>
                        <td><?php echo (($field->widgetView($profile))?$field->widgetView($profile):CHtml::encode((($field->range)?Profile::range($field->range,$profile->getAttribute($field->varname)):$profile->getAttribute($field->varname)))); ?></td>
                    </tr>
                    <?php
                }//$profile->getAttribute($field->varname)
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
                <td><?php echo CHtml::encode(User::itemAlias("UserStatus",$model->status)); ?></td>
            </tr>
        </table>
    </section>
    <div >
        user apps here this is for public 
    </div>
</div>