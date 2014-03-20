<div class="cell menu">
    <ul class="stat left nav">
        <?php foreach($cateList as $cateModel): ?>
        <li class="">
            <a href="<?php echo controller()->createUrl('/friend/relationship/viewAll',
            array('u'=>$this->userId,'cateId'=>$cateModel->primaryKey)
            ) ?>" class="">
                <span class="data">
                    <?php echo $cateModel->mbr_count ; ?>
                </span>
                <?php echo $cateModel->name ?>
            </a>
        </li>
        <?php endforeach ; ?>
    </ul>
</div>