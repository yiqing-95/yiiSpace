<?php $this->widget('my.widgets.boxslider.JBoxSlider', array(
   // 'container' => '.slider-viewport',
    'debug'=>true ,
    'options' =>array(),
));

?>
<?php $imgBaseUrl = 'http://www.philparsons.co.uk/demos/box-slider/'; ?>

<div id="viewport-shadow">

    <a href="#" id="prev" title="go to the next slide"></a>
    <a href="#" id="next" title="go to the next slide"></a>

    <div id="viewport">
        <div id="box">
            <figure class="slide">
                <img src="<?php echo $imgBaseUrl; ?>/img/the-battle.jpg">
            </figure>
            <figure class="slide">
                <img src="<?php echo $imgBaseUrl; ?>/img/hiding-the-map.jpg">
            </figure>
            <figure class="slide">
                <img src="<?php echo $imgBaseUrl; ?>/img/theres-the-buoy.jpg">
            </figure>
            <figure class="slide">
                <img src="<?php echo $imgBaseUrl; ?>/img/finding-the-key.jpg">
            </figure>
            <figure class="slide">
                <img src="<?php echo $imgBaseUrl; ?>/img/lets-get-out-of-here.jpg">
            </figure>
        </div>
    </div>

    <div id="time-indicator"></div>
</div>
<style>
        /* overfow will get set to visible on initialisation of the plugin */
    #viewport { width: 560px; height: 380px; overflow: hidden }
</style>

<script type="text/javascript">

    $(function () {

        var $box = $('#box')
            , $indicators = $('.goto-slide')
            , $effects = $('.effect')
            , $timeIndicator = $('#time-indicator')
            , slideInterval = 5000
            , effectOptions = {
                'blindLeft': {blindCount: 15}
                , 'blindDown': {blindCount: 15}
                , 'tile3d': {tileRows: 6, rowOffset: 80}
                , 'tile': {tileRows: 6, rowOffset: 80}
            };

        // This function runs before the slide transition starts
        var switchIndicator = function ($c, $n, currIndex, nextIndex) {
            // kills the timeline by setting it's width to zero
            $timeIndicator.stop().css('width', 0);
            // Highlights the next slide pagination control
            $indicators.removeClass('current').eq(nextIndex).addClass('current');
        };

        // This function runs after the slide transition finishes
        var startTimeIndicator = function () {
            // start the timeline animation
            $timeIndicator.animate({width: '680px'}, slideInterval);
        };

        // initialize the plugin with the desired settings
        $box.boxSlider({
            speed: 1000
            , autoScroll: true
            , timeout: slideInterval
            , next: '#next'
            , prev: '#prev'
            , pause: '#pause'
            , effect: 'scrollVert3d'
            , onbefore: switchIndicator
            , onafter: startTimeIndicator
        });

        startTimeIndicator(); // start the time line for the first slide

        // Paginate the slides using the indicator controls
        $('#controls').on('click', '.goto-slide', function (ev) {
            $box.boxSlider('showSlide', $(this).data('slideindex'));
            ev.preventDefault();
        });

        // This is for demo purposes only, kills the plugin and resets it with
        // the newly selected effect FIXME clean this up!
        $('#effect-list').on('click', '.effect', function (ev) {
            var $effect = $(this)
                , fx = $effect.data('fx')
                , extraOptions = effectOptions[fx];

            $effects.removeClass('current');
            $effect.addClass('current');
            switchIndicator(null, null, 0, 0);

            if (extraOptions) {
                $.each(extraOptions, function (opt, val) {
                    $box.boxSlider('option', opt, val);
                });
            }

            $box.boxSlider('option', 'effect', $effect.data('fx'));

            ev.preventDefault();
        });

    });
</script>
</script>