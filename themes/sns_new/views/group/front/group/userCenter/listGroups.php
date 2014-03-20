<div class="cell">
    <div class="col">
        <div class="col width-7of9 tablet-width-1of2">
            adfsgfsdfg
            <?php $this->widget('zii.widgets.CListView', array(
                'id' => 'group-list',
                'dataProvider' => $dataProvider,
                'template' => '{items}',
                'itemsTagName' => 'ul',
                'itemsCssClass' => 'nav',
                'htmlOptions' => array(
                    'class' => 'gallery cell',
                ),
                'itemView' => 'userSpace/_groupView',
            )); ?>
        </div>

    </div>
</div>


