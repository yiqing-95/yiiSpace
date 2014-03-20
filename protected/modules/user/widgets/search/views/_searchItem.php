<?php $user = $data ;  ?>
<div class="post">
    <div class="" style="margin: 5px ;">
        <div class="">
            <img src="<?Php echo $user->getIconUrl(); ?>"
                 width="360px" height="360px"
                 alt=""/>

        </div>
        <div >
            <?php echo CHtml::link(CHtml::encode($user->username), array("/user/user/space", "u" => $user->id),array(
                'target'=>'_blank'
            ))  ?>
            <br/>
        </div>
    </div>
</div>