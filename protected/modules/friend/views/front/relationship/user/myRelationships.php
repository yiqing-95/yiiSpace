<h1>My Relationships</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'id' => 'relationship-list',
    'dataProvider' => $dataProvider,
    'itemView' => 'user/_myRelationshipsView',
)); ?>