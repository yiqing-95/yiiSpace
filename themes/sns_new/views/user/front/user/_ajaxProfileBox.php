<div class="cell mediaobject">
    <div class="col width-fit">
        <div class="cell">
            <img alt="" width="120" src="<?php  echo $user->getIconUrl(); ?>">
        </div>
    </div>
    <div class="col width-fill">
        <div class="cell">
            <a href="#">Chen</a> says:<br>
            <p>
            <div class="cell">
                <dl>
                    <dt>Apple</dt>
                    <dd>is a fruit.</dd>
                 <?php print_r($user->attributes) ?>
                </dl>
            </div>
            </p>
        </div>
    </div>
</div>