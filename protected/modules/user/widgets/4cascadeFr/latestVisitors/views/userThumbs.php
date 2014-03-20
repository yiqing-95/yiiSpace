<ul class="thumbnails">
    <?php foreach($users as $user): ?>
    <li class="span1">
        <a href="<?php echo  UserHelper::getUserSpaceUrl($user->primaryKey); ?>" class="thumbnail">
            <img src="<?php echo $user->getIconUrl() ; ?>" alt="" width="80px" height="80px">
            <h5><?php echo $user->username; ?></h5>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
