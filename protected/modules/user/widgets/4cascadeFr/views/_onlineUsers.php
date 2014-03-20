<ul class="nav">
    <?php
    foreach ($users as $user): ?>
        <li>


        <div class="cell">
            <div class="span3 thumbnail">
                <a href="<?php echo UserHelper::getUserSpaceUrl($user->id); ?>" target="_blank">
                    <img src="<?Php echo $user->getIconUrl(); ?>"
                         width="90px" height="90px"
                         alt=""/>
                </a>

            </div>
            <div class="span8">
                <?php echo CHtml::link(CHtml::encode($user->username), array("/user/user/space", "u" => $user->id), array(
                    'target' => '_blank'
                ))  ?>
                <br/>
            </div>
        </div>
        </li>
    <?php endforeach; ?>

</ul>