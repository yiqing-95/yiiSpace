<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile"); ?>


        <div id="recent_statuses">
            <?php
            Yii::app()->runController('/status/status/listRecentStatus');
            ?>
        </div>



