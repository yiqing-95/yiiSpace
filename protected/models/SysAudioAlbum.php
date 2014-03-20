<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-11
 * Time: 上午9:16
 */

class SysAudioAlbum extends SysAlbum{

    public $type = 'SysAudio';

    /**
     * 看下samdark的wiki 如何处理表继承问题的
     *
     * 这个字段在表连接时注意别名冲突啊！t.type='SysAudio' 是有问题的
     *
     * @return array
     */
    function defaultScope(){
        return array(
            'condition'=>"type='SysAudio'",
        );
    }


    public function rules() {
        return array(
            array('caption', 'required'),
            array('uid, create_time, last_obj_id, allow_view', 'numerical', 'integerOnly'=>true),
            array('caption, location', 'length', 'max'=>128),
            array('cover_uri, description', 'length', 'max'=>255),
            array('type', 'length', 'max'=>20),
            array('status', 'length', 'max'=>7),
            array('cover_uri, location, description, type, uid, status, create_time, obj_count, last_obj_id, allow_view', 'default', 'setOnEmpty' => true, 'value' => null),
            array('id, caption, cover_uri, location, description, type, uid, status, create_time, obj_count, last_obj_id, allow_view', 'safe', 'on'=>'search'),
        );
    }
} 