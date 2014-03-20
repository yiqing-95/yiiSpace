<?php

Yii::import('application.models._base.BaseSysAlbum');

/**
 * @see http://www.yiiframework.com/wiki/198/single-table-inheritance/
 *
 * Class SysAlbum
 */
class SysAlbum extends BaseSysAlbum
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public $type = 'SysAlbum';

    /**
     * 看下samdark的wiki 如何处理表继承问题的
     *
     * 这个字段在表连接时注意别名冲突啊！t.type='SysAudio' 是有问题的
     *
     * @return array
     */
    function defaultScope(){
        return array(
            'condition'=>"type='SysAlbum'",
        );
    }

}