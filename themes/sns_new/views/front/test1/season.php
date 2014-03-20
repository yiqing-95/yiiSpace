<style type="text/css">
    #test{
        border: solid #3399cc;
        background-color: #ffffcc;
    }
</style>

<?php
$this->widget('my.widgets.ESeasonWidget', array(
 'selector'=>'#test',
));
?>

<div id="test">
    use firebug to see my class
</div>

    <p>
        <?php
        echo  'current season is :'. ESeasonWidget::getCurrentSeason();

        ?>
    </p>
