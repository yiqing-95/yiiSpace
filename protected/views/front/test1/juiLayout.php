<?php
$this->widget('my.widgets.juiLayout.JUiLayout', array(
));
?>

<script>
    $(document).ready(function () {
        $('#layout').layout({ applyDefaultStyles: true });
    });
</script>
    <div id="layout">
        <div class="ui-layout-center">Center
            <p><a href="http://layout.jquery-dev.net/demos.html">Go to the Demos page</a></p>
            <p>* Pane-resizing is disabled because ui.draggable.js is not linked</p>
            <p>* Pane-animation is disabled because ui.effects.js is not linked</p>
        </div>
        <div class="ui-layout-north">North</div>
        <div class="ui-layout-south">South</div>
        <div class="ui-layout-east">East</div>
        <div class="ui-layout-west">West</div>
    </div>
