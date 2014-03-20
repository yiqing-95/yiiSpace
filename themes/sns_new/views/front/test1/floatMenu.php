<!--浮动菜单测试-->
<style>

    /* Header */
    #header {
        background: -webkit-gradient(linear, left top, left bottom,	color-stop(0, rgb(117,18,41)), color-stop(1, rgb(79,7,24)));
        height: 86px;
    }

    /* Top Level Menu */
    #header ul {
        margin: 0 auto;
        width: 400px;
    }

    #header ul li {
        display: block;
        float: left;
        margin: 40px 0 0;
        width: 100px;
    }

    #header ul li a {
        color: #FFF;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
    }

    /* Sub Level Nav */
    #header ul li ul {
        background: #F4F4F4;
        border: 0px solid #000;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5);
        margin: 10px 0 0 -46px;
        padding: 0 10px 0;
        position: relative;
        width: 126px;
    }

    #header ul li ul li {
        border-bottom: 1px solid #CCC;
        display: block;
        float: none;
        height: 14px;
        padding: 8px 0;
        text-align: center;
        width: 126px;
        margin: 0;
    }

    #header ul li ul li a {
        color: #620d20;
        text-shadow: none;
    }

    #header ul li ul li a:hover {
        color: #000;
    }

    #header ul li ul li:last-child:not(li.arrow) {
        border: 0;
    }

    /* Arrow
    .arrow {
        background: url(arrow.png) no-repeat;
        border: 0;
        display: none;
        position: absolute;
        top: -10px;
        left: 63px;
        height: 11px;
        width: 20px;
        text-indent: -9999px;
    }
    */

    .arrow {
        height: 0;
        width: 0;
        border: 4px solid transparent;
    }
    .arrow.up {
        border-bottom-color: #000;
    }
    .arrow.down {
        border-top-color: #000;
    }
</style>
<div id="header">
    <ul>
        <li><a href="#">list item</a>
            <ul>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
            </ul>
        </li>
        <li><a href="#">list item</a></li>
        <li><a href="#">list item</a>
            <ul>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
            </ul>
        </li>
        <li><a href="#">list item</a>
            <ul>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
                <li><a href="#">list item</a></li>
            </ul>
        </li>
    </ul>
</div>


<!--测试下浮动菜单！-->
<script type="text/javascript">
    $(function(){

        //Hide SubLevel Menus
        $('#header ul li ul').hide();

        //OnHover Show SubLevel Menus
        $('#header ul li').hover(
            //OnHover
            function(){
                //Hide Other Menus
                $('#header ul li').not($('ul', this)).stop();

                //Add the Arrow
                $('ul li:first-child', this).before(
                    '<li class="arrow down">arrow</li>'
                );

                //Remove the Border
                $('ul li.arrow.down', this).css('border-bottom', '0');

                // Show Hoved Menu
                $('ul', this).slideDown();
            },
            //OnOut
            function(){
                // Hide Other Menus
                $('ul', this).slideUp();

                //Remove the Arrow
                $('ul li.arrow.down', this).remove();
            }
        );

    });
</script>

<!--/浮动菜单测试-->