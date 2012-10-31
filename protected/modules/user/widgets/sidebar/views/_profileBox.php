<?php
$picId = rand(1, 5);
$userPhotoUrl = empty($profile->photo) ? PublicAssets::instance()->url("images/user/avatars/{$picId}.jpg") : bu($profile->photo);

?>
    <div class="thumbnail">
        <img src="<?Php echo $userPhotoUrl; ?>" width="120px" height="120px" alt=""/>

        <h5><?php echo CHtml::encode($model->username); ?></h5>

        <p>
          <ul>
        <li>
            <?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?>:
            <?php echo $model->create_at; ?>

        </li>
  <li>
      <?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?>:
      <?php echo $model->lastvisit_at; ?>
  </li>
          </ul>
        </p>
    </div>


<?php
/*
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

*/?>