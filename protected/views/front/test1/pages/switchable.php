<?php
$this->widget('my.widgets.jswitchable.JSwitchable', array(

));
$bu = bu();
$imgBaseUrl = 'http://switchable.mrzhang.me/';
?>

<!-- styles for this demo -->
<style>
    #slide {
        /* Required Styles */
        position: relative;
        width: 550px;
        height: 310px;
        overflow: hidden;

        border: 10px solid #444;
        border-radius: 5px;
        box-shadow: 0 0 6px rgba(0, 0, 0, .4);
    }
    #slide li {
        float: left;
    }
    #slide img {
        display: block;
        width: 550px;
        height: 310px;
    }
    .switchable-triggers {
        position: absolute;
        right: 3px;
        bottom: 6px;
    }
    .switchable-triggers li {
        display: inline-block;
        float: left;
        width: 20px;
        height: 20px;
        margin: 0 3px;
        border-radius: 20px;
        background: rgba(255, 255, 255, .7);
        color: #444;
        font-size: 13px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
    }
    .switchable-triggers li.current {
        background: rgba(138, 181, 22, .7);
        color: #fff;
    }
    .button {
        width: 570px;
        margin-top: 10px;
        text-align: center;
    }
    .button a {
        font: normal 25px LeagueGothic;
        text-shadow: 1px 2px 2px rgba(0, 0, 0, .5);
    }
</style>

<div id="slide">
    <ul>
        <li><img src="<?php echo $imgBaseUrl?>/images/p1.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p2.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p3.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p4.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p5.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p6.jpg" /></li>
        <li><img src="<?php echo $imgBaseUrl?>/images/p7.jpg" /></li>
    </ul>
</div>
<div class="button">
    <a href="#" id="j-button">Play</a>
</div>

<script type="text/javascript">
    $(function() {
        var Slide = $('#slide').switchable({
            putTriggers: 'appendTo',
            panels: 'li',
            initIndex: -1, // display the last panel
            effect: 'scrollRight', // taking effect when autoplay == true
            easing: 'cubic-bezier(.455, .03, .515, .955)', // equal to 'easeInOutQuad'
            end2end: true, // if set to true, loop == true
            loop: false, // not taking effect, because end2end == true
            autoplay: true,
            interval: 5,
            api: true // if set to true, Switchable returns API
        }).pause();

        var btn = $('#j-button');
        btn.click(function(e){
            e.preventDefault();

            if ( Slide.paused ) {
                Slide.play();
                btn.text('Pause');
            }
            else {
                Slide.pause();
                btn.text('Play');
            }
        });
    });
</script>