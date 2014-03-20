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

<hr />
<?php

$this->widget('my.widgets.powerSwitch.JPowerSwitch',array(
    // 'selector'=>'#trigger'
)); ?>
<div class="zxx_body">
    <div class="zxx_constr">
        <!--  body of index.php -->
        <div class="index_box">
            <div id="indexSlide1" class="index_slide index_explain">
            	<span class="index_text">好苦恼啊！首页应该放些什么东西呢？<br>
                要不，什么都来一点？幸福好滋味！^_^</span><i class="index_valign"></i>
            </div>
            <div id="indexSlide2" class="index_slide index_hidden">
                <img data-src="./img/index/hello.jpg" alt="大家好">
            </div>
            <div id="indexSlide3" class="index_slide index_explain index_hidden">
            	<span class="index_text">喂喂喂，就一张红白花花的图，好平淡哦~<br>
                要不，加点作料什么的？比方说小标题？</span><i class="index_valign"></i>
            </div>
            <div id="indexSlide4" class="index_slide index_hidden">
                <p class="index_caption">国庆节野钓渔获，超赞的野生鲫鱼！Photo by <a href="http://www.zhangxinxu.com/" class="index_caption_a">zhangxinxu</a></p>
                <img data-src="./img/index/hello.jpg" alt="大家好">
            </div>
            <div id="indexSlide5" class="index_slide index_explain index_hidden">
                <span class="index_text">或者来段魅惑的描述性文字？</span><i class="index_valign"></i>
            </div>
            <div id="indexSlide6" class="index_slide index_descr index_hidden">
                <img data-src="./img/index/hello.jpg" class="index_anim_img" alt="大家好">
                <div class="index_cell">
                    <div class="index_article">
                        <h4>金秋野钓记</h4>
                        <p>金秋有中秋佳节，还有国庆长假，都是钓鱼的好时节啊！</p>
                        <p>但是，夫人他要学车。作为新世纪的好男人，有义务负责接送。因此，去天塘钓鱼就不现实，每次接送都要废掉好几个小时，是不划算的。因此，就去学车地点附近的野河钓鱼。</p>
                        <p>哈哈，完全是展示小时候多年的野钓技艺哈~ 没怎么认真钓，都有几斤的野生鲫鱼上钩，还有很多鳑鲏，雷管这些猫鱼，真是不亦乐乎……</p>
                        <p><a href="http://www.zhangxinxu.com/life/?p=418">查看全部 &raquo;</a></p>
                    </div>
                </div>
            </div>
            <div id="indexSlide7" class="index_slide index_explain index_hidden">
                <span class="index_text">下面要出现的是？……</span><i class="index_valign"></i>
            </div>
            <div id="indexSlide8" class="index_slide index_hidden">
                <iframe height="100%" width="100%" data-src="http://player.youku.com/embed/XNjE0Nzc0ODYw" frameborder=0 allowfullscreen></iframe>
            </div>
        </div>
        <div id="indexControl" class="index_control"></div>
        <p id="indexNav" class="index_nav">
            <a href="javascript:" class="on" data-rel="indexSlide1">1</a>
            <a href="javascript:" data-rel="indexSlide2">2</a>
            <a href="javascript:" data-rel="indexSlide3">3</a>
            <a href="javascript:" data-rel="indexSlide4">4</a>
            <a href="javascript:" data-rel="indexSlide5">5</a>
            <a href="javascript:" data-rel="indexSlide6">6</a>
            <a href="javascript:" data-rel="indexSlide7">7</a>
            <a href="javascript:" data-rel="indexSlide8">8</a>
        </p>
    </div>
</div>


<script>
    $("#indexNav a").powerSwitch({
        animation: "translate",
        classAdd: "on",
        classPrefix: "index_",
        container: $("#indexControl"),
        onSwitch: function(target) {
            var eleLazyLoad = target.find("img, iframe").get(0), index = target.data("index");
            if (eleLazyLoad && !eleLazyLoad.src) {
                eleLazyLoad.src = eleLazyLoad.getAttribute("data-src");
            }

            // 第4帧标题的淡入淡出效果
            if (index == 3) {
                setTimeout(function () {
                    target.find("p").fadeIn();
                }, 250);
            } else {
                setTimeout(function () {
                    $("#indexSlide4 p").hide();
                }, 250);
            }
            // 第6帧CSS3驱动的动画效果
            if (index == 5) {
                setTimeout(function () {
                    target.addClass("active");
                }, 50);
            } else {
                setTimeout(function () {
                    $("#indexSlide6").removeClass("active");
                }, 250);
            }
        }
    });
</script>