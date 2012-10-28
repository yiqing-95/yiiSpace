
<!--三列布局-->
<?php if (Layout::hasRegions('left', 'right')): ?>

<?php $this->beginContent('//layouts/_main'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <?php Layout::renderRegion('left'); ?>
            <!--Sidebar content-->
        </div>
        <div class="span8">
            <?php echo $content; ?>
            <!--Body content-->
        </div>
        <div class="span2">
            <?php Layout::renderRegion('right'); ?>
            <!--Sidebar content-->
        </div>
    </div>
</div>
<?php $this->endContent(); ?>

<?php endif; ?>

<!--二列左侧 或者右侧布局：-->

<?php
if (Layout::hasRegions('left') && !Layout::hasRegion('right')): ?>
<?php $this->beginContent('//layouts/_main'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <?php Layout::renderRegion('left'); ?>
            <!--Sidebar content-->
        </div>
        <div class="span10">
            <?php echo $content; ?>
            <!--Body content-->
        </div>
    </div>
</div>
<?php $this->endContent(); ?>

<?php endif; ?>

<!--二列右边有侧导航栏：-->
<?php
if (Layout::hasRegions('right') && !Layout::hasRegion('left')): ?>

<?php $this->beginContent('//layouts/_main'); ?>
<div class="container-fluid">
    <div class="row-fluid">

        <div class="span10">
            <?php echo $content; ?>
            <!--Body content-->
        </div>

        <div class="span2">
            <?php Layout::renderRegion('right'); ?>
            <!--Sidebar content-->
        </div>
    </div>
</div>
<?php $this->endContent(); ?>

<?php endif; ?>

<!--一列布局-->
<?php if (!Layout::hasRegion('left') && !Layout::hasRegion('right')) : ?>

<?php $this->beginContent('//layouts/_main'); ?>
<div class="container">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>
<?php endif; ?>