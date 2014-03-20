<div class="border-bottom cell">
    <div class="cell">
        <h3><?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?></h3>

        <div class="col content">
            <p>

            </p>
            <?php
            $this->beginWidget('CMarkdown', array('purifyOutput' => true));
            echo $data->content;
            $this->endWidget();
            ?>
            <a href="#">[ read more ]</a>
            <a href="<?php echo $this->createUrl('update',array('id'=>$data->primaryKey)); ?>">修改</a>
        </div>
        <div class="col">
            posted by <?php echo $data->author->username . ' on ' . date('F j, Y', $data->created); ?>

            <div>
                <b>Tags:</b>
                <?php echo implode(', ', $data->tagLinks); ?>
                <br/>
                <?php echo CHtml::link('Permalink', $data->url); ?> |
                <?php // echo CHtml::link("Comments ({$data->commentCount})", $data->url . '#comments'); ?> |
                Last updated on <?php echo date('F j, Y', $data->updated); ?>
            </div>
        </div>
    </div>
</div>