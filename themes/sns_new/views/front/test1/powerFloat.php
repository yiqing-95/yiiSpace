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


<?php $this->widget('my.widgets.powerFloat.JPowerFloat',array(
   'selector'=>'#trigger'
)); ?>
<a id="trigger" href="javascript:;" rel="targetBox">经过我</a>

<div id="targetBox">
    hi  wo zai zhe na!
</div>

<p>带链接的文字下拉：<a id="trigger9" href="/wordpress/">更多文章▼</a></p>
<script type="text/javascript">
    $(function(){
        $("#trigger").powerFloat();
        $("#trigger9").powerFloat({
            width: 250,
            target: [
                {
                    href: "##",
                    text: "这是文章1的说"
                },
                {
                    href: "##",
                    text: "啊，看，文章2"
                },
                {
                    href: "##",
                    text: "啊啦，不好，我把文章3忘家里了！"
                },
                {
                    href: "##",
                    text: "马萨噶，这就是传说中的...文章4..."
                },
                {
                    href: "##",
                    text: "什么嘛，就是文章5，害我白期待一场"
                }
            ],
            targetMode: "list"
        });

        $(".tipTrigger").powerFloat({
            offsets: {
                x: -10,
                y: 22
            },
            showDelay: 200,

            hoverHold: false,

            targetMode: "tip",
            targetAttr: "tip",
            position: "3-4"
        });

        // 图片测试
        $("#trigger14").powerFloat({
            targetMode: "ajax",
            targetAttr: "href",
            hoverFollow: "y",
            position: "6-8"
        });
    });

</script>

<a class="tipTrigger" href="###" tip="摸我">摸我</a>
<a class="tipTrigger" href="###" tip="我也要">我也要</a>
<a class="tipTrigger" href="###" tip="还有我">还有我</a>

<div>
    <style>
        .dib { display: inline-block; }
    </style>
    <a class="dib" id="trigger14" href="<?php echo bu('public/images/banner1.jpg');?>">
        <?php
           $imgIds = range(1,5) ;
           $imgIdIdx  = array_rand($imgIds);
           $imgId = $imgIds[$imgIdIdx];
           $imgPath = bu("public/images/tests/{$imgId}.jpg");
            echo CHtml::image($imgPath,'',array('width'=>'300px','height'=>'300px'));
        ?>
<!--        <img src="--><?php //echo bu('public/images/tests/5.jpg');?><!--"  width="300px" height="300px"/>-->
    </a>
</div>

<div id="targetBox2">here is content !</div>
<a id="trigger1" href="javascript:;" rel="targetBox">默认rel加载</a>
<script type="text/javascript">
   /// $(function(){
        $("#trigger1").powerFloat({
            target: $("#targetBox2")
        });
    // });
</script>

HTML代码：
<a id="trigger4" href="javascript:;"
   rel="http://localhost/sns_my/rcyxw2013/public/images/banner1.jpg">rel属性显示图片</a>
JS代码：
<script type="text/javascript">
    $("#trigger4").powerFloat({ targetMode: "ajax" });
</script>
