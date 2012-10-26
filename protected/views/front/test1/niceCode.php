<?php
$this->breadcrumbs=array(
	'Test',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
    <div class="panel">
        Jerry Kong(280978331)  16:01:38
        $cids = array('a'=>3,'b'=>5);
        $user = Yii::app()->db->createCommand()
        ->select('cid, dept')
        ->from('{{wjy_config}}')
        ->where(array('and', 'status=1 and type=1', array('in', 'cid', $cids)))
        ->order('weight desc')
        ->queryAll();

    </div>
