<ul>
<?php foreach ($this->getAllCategories() as $cate): ?>
<li>
<?php echo CHtml::link($cate->name,
    array('my/index',
         'cate'=>$cate->id,
        ));  ?>
    
</li>
<?php endforeach; ?>
</ul>