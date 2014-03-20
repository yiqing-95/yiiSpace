<?php $this->widget('my.widgets.artDialog.ArtDialog'); ?>
<script type="text/javascript">
    // 普通调用
    $.dialog({content:'hello world!'});
</script>

<div class="header">

    <h1>jQuery Bubble Popup</h1>
    <h2>test plugin</h2>

</div>
<div class="content">
    <br />
    <br />
    <br />

    <div class="button">mouse over here!</div>

    <br />
    <br />
    <strong>Take a look to the HTML source of this page to learn how the plugin works!</strong>
</div>

<?php $this->widget('my.widgets.bubblePopup.JBubblePopup'); ?>
<script type="text/javascript">
    <!--
    $(document).ready(function(){

        $('.button').CreateBubblePopup({

            position : 'top',
            align	 : 'right',

            innerHtml: 'Take a look to the HTML source of this page <br /> \
												to learn how the plugin works!',

            innerHtmlStyle: {
                color:'#FFFFFF',
                'text-align':'center'
            },

            themeName:  'green',
            themePath:  '<?php echo JBubblePopup::getAssetsUrl()?>/jquerybubblepopup-themes'

        });
    });
    //-->
</script>
