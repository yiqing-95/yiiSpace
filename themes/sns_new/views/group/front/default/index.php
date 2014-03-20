<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1><?php echo $this->uniqueId . '/' . $this->action->id; ?></h1>

<p>
This is the view content for action "<?php echo $this->action->id; ?>".
The action belongs to the controller "<?php echo get_class($this); ?>"
in the "<?php echo $this->module->id; ?>" module.
</p>
<p>
You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
</p>
<div class="container site-body">

    <div class="cell">


        <div class="col width-7of9">

            <h3>系统推荐</h3>

            <?php $this->widget('group.widgets.RecommendGroupWidget') ?>

        </div>

        <div class="col width-fill">
        sdfsdf
        </div>
    </div>

</div>
