
<?php
/**
 *  nested widget (CListView is in CommentsListWidget ) !  you can expose config from the outer widget
 * then merger with the default config : CMap::mergerArray($defaultConfig,$customConf)
 */
$this->widget('zii.widgets.CListView', array(
    'id'=>$this->id ,
   // 'ajaxUrl'=>$this->ajaxUrl,
    'htmlOptions'=>array(
      'class'=>'list-view comment',
    ),
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'viewData'=>array('userProfiles'=>$userProfiles),
    'template'=>'{pager}{items}{pager}',
));

?>