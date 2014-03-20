<h1>Pending Relationships</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'relationship-list',
    'dataProvider' => $dataProvider,
    'itemView' => '_pendingRelationView',
)); ?>

<?php
$this->widget('wij.WijDialog', array(
    'selector' => '#dialog-message',
    'theme' => EWijmoWidget::THEME_COBALT,
    'options' => 'js:
{
    autoOpen: false,
    height: 180,
    width: 400,
    modal: true,
    buttons: {
    Ok: function () {
    $(this).wijdialog("close");
    }
    },
captionButtons: {
        pin: { visible: false },
        refresh: { visible: false },
        toggle: { visible: false },
        minimize: { visible: false },
        maximize: { visible: false }
    }
}'
));?>

<div id="dialog-message" title="congrats !">
    <p>
        <span class="ui-icon ui-icon-circle-check"></span>
        relation has handled successfully!
    </p>
</div>

<script type="text/javascript">
    function handleRelation(linkEle, ctl_type) {
        if(ctl_type == 'approve'){
            var url = "<?php echo $this->createUrl('/friend/relationship/approve'); ?>";
        }else if(ctl_type == "reject"){
            var url = "<?php echo $this->createUrl('/friend/relationship/reject'); ?>";
        }

        var params = {
            "relation_id":$(linkEle).attr("relation_id")
        };
        $.post(url, params, function (resp) {
            if(resp.success == true){
                $('#dialog-message').wijdialog('open');
            }else{
                alert('sorry handle failure ');
            }

        }, "json");
    }
</script>