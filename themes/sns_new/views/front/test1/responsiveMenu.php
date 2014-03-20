<?php $this->widget('my.widgets.artDialog.ArtDialog'); ?>
<script type="text/javascript">
    // 普通调用
    $.dialog({content:'hello world!'});
</script>

<nav id="resp-nav" role="navigation">
    <a href="#resp-nav" title="Show navigation">Show navigation</a>
    <a href="#" title="Hide navigation">Hide navigation</a>
    <ul>
        <li><a href="/">Home</a></li>
        <li>
            <a href="/">Blog</a>
            <ul>
                <li><a href="/">Design</a></li>
                <li><a href="/">HTML</a></li>
                <li><a href="/">CSS</a></li>
                <li><a href="/">JavaScript</a></li>
            </ul>
        </li>
        <li>
            <a href="/">Work</a>
            <ul>
                <li><a href="/">Web Design</a></li>
                <li><a href="/">Typography</a></li>
                <li><a href="/">Front-End</a></li>
            </ul>
        </li>
        <li><a href="/">About</a></li>
    </ul>
</nav>

<style>
    #resp-nav
    {
        /* container */
    }
    #resp-nav > a
    {
        display: none;
    }
    #resp-nav li
    {
        position: relative;
    }

        /* first level */

    #resp-nav > ul
    {
        height: 3.75em;
    }
    #resp-nav > ul > li
    {
        width: 25%;
        height: 100%;
        float: left;
    }

        /* second level */

    #resp-nav li ul
    {
        display: none;
        position: absolute;
        top: 100%;
    }
    #resp-nav li:hover ul
    {
        display: block;
    }

    @media only screen and ( max-width: 40em ) /* 640 */
    {
        #resp-nav
        {
            position: relative;
        }
        #resp-nav > a
        {
        }
        #resp-nav:not( :target ) > a:first-of-type,
        #resp-nav:target > a:last-of-type
        {
            display: block;
        }

        /* first level */

        #resp-nav > ul
        {
            height: auto;
            display: none;
            position: absolute;
            left: 0;
            right: 0;
        }
        #resp-nav > ul.active
        {
            display: block;
        }
        #resp-nav > ul > li
        {
            width: 100%;
            float: none;
        }

        /* second level */

        #resp-nav li ul
        {
            position: static;
        }
    }
</style>
<script type="text/javascript">
    /*
     AUTHOR: Osvaldas Valutis, www.osvaldas.info
     */



    ;(function(t,n,r,i){t.fn.doubleTapToGo=function(i){if(!("ontouchstart"in n)&&!n.navigator.msPointerEnabled&&!navigator.userAgent.toLowerCase().match(/windows phone os 7/i))return false;this.each(function(){var n=false;t(this).on("click",function(e){var r=t(this);if(r[0]!=n[0]){e.preventDefault();n=r}});t(r).on("click touchstart MSPointerDown",function(e){var r=true,i=t(e.target).parents();for(var s=0;s<i.length;s++)if(i[s]==n[0])r=false;if(r)n=false})});return this}})(jQuery,window,document);

    $(function(){
        $( '#resp-nav li:has(ul)' ).doubleTapToGo();
    });

</script>
<div>
    here http://www.sooperthemes.com/sites/default/files/SooperFish/example.html
    and here : http://osvaldas.info/examples/drop-down-navigation-touch-friendly-and-responsive/
    http://osvaldas.info/drop-down-navigation-responsive-and-touch-friendly
    http://bradfrostweb.com/blog/web/responsive-nav-patterns/
</div>