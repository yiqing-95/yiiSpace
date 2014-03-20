<?php foreach($groupList as $group): ?>

    <div class="col">
        <div class="cell">
            <div class="col width-fit mobile-sizefit">
                <span class="icon icon-user">
                    <a class="" href="<?php echo Yii::app()->controller->createUrl('/group/group/view',array('id'=>$group->primaryKey)); ?>">
                        <?php echo $group->name ; ?>
                    </a>

                </span>
            </div>
            <div class="col width-fill mobile-sizefill">

                <div class="col">
                    <div class="col width-fit mobile-sizefit">
                        <div class="cell">
                            <img src="../vendor/assets-1.0/img/32x32.gif" alt="">
                        </div>
                    </div>
                    <div class="col width-fill mobile-sizefill">
                        <div class="cell">
                        <?php echo $group->description ; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>