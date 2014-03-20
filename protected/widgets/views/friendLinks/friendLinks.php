<div class="friend-links">
   <?php foreach($links as $link): ?>
      <?php echo CHtml::link($link->name,$link->url,array('target'=>'_blank')) ;?>|
    <?php endforeach; ?>
</div>