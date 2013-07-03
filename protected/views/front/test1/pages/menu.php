<?php $objectName = 'photo';
   $objectId = 3;
?>

<div id="photo_comments">
    <script type="text/javascript">
        $(function(){
            $("#photo_comments").load("<?php echo  $this->createUrl('/sys/comment',array('objectName'=>$objectName,'objectId'=>$objectId));?>")
        });
    </script>
</div>
menu test
<div class="dropdown">
    <a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
        Dropdown
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
        ...
    </ul>
</div>

<div class="dropdown">
    <!-- Link or button to toggle dropdown -->
    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
        <li><a tabindex="-1" href="#">Action</a></li>
        <li><a tabindex="-1" href="#">Another action</a></li>
        <li><a tabindex="-1" href="#">Something else here</a></li>
        <li class="divider"></li>
        <li><a tabindex="-1" href="#">Separated link</a></li>
    </ul>
</div>

    <?php $this->widget('bootstrap.widgets.TbDropdown',array());?>
<script type="text/javascript">
    $(function(){
        $('.dropdown-toggle,.dropdown-menu').dropdown();
    })
</script>