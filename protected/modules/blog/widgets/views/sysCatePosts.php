<ul>
    <?php foreach($posts as $data):?>
        <li><?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?></li>

    <?php endforeach ;?>
</ul>