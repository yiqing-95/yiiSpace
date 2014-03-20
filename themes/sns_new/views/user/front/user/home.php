<?php $this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Profile"); ?>


<?php // echo Yii::app()->controller->getLayoutFile($this->layout ); ?>
        <div id="recent_statuses">
            <?php CascadeFr::beginCollapsible();?>
         顶顶顶顶
            <?php CascadeFr::endCollapsible();?>

            <?php CascadeFr::beginCollapsible();?>
            顶顶顶顶222
            <?php CascadeFr::endCollapsible();?>

            <?PHP $this->widget('user.widgets.usercenter.AccountControlBox');?>

        </div>



