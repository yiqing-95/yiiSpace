<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile"); ?>
<div>
    <section class="two columns">
        <?php

        $this->widget('user.widgets.sidebar.UserSidebar');

        ?>
    </section>
    <section class="seven columns">

        <div id="recent_statuses">
            <?php
            Yii::app()->runController('/status/status/listRecentStatus');
            ?>
        </div>

    </section>
</div>

