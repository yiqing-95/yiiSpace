
        <?php
        if(Layout::hasRegions('sidebar.left','sidebar.right'))
        {
            $tagClass = ' class="sidebars clearfix"';
        }else if(Layout::hasRegions('sidebar.left'))
        {
            $tagClass = ' class="sidebar-left clearfix"';
        }else if(Layout::hasRegions('sidebar.right'))
        {
            $tagClass = ' class="sidebar-right clearfix"';
        }else{
            $tagClass = '';
        }
        ?>
        <div id="canvas-content"<?php echo $tagClass; ?>>

            <?php if(Layout::hasRegion('sidebar.left')): ?>
            <!-- sidebar-left -->
            <div id="sidebar-left" class="sidebar">
                <div class="sidebar-content">
                    <?php Layout::renderRegion('sidebar.left'); ?>
                </div>
            </div>
            <!-- /sidebar-left -->
            <?php endif; ?>

            <div id="content-container">
                <!-- content -->
                <div id="content">
                    <?php echo $content; ?>
                </div>
                <!-- /content -->
            </div>

            <?php if(Layout::hasRegion('sidebar.right')): ?>
            <!-- sidebar-right -->
            <div id="sidebar-right" class="sidebar">
                <div class="sidebar-content">
                    <?php Layout::renderRegion('sidebar.right'); ?>
                </div>
            </div>
            <!-- /sidebar-right -->
            <?php endif; ?>
