<ul>
<?php foreach ($this->findAllPostDate() as $v): ?>
<li>
<?php echo CHtml::link("$v->year$this->year$v->month$this->month($v->posts)",
    array('post/index',
         'year'=>$v->year,
         'month'=>$v->month,
        ));  ?>
    
</li>
<?php endforeach; ?>
</ul>