<?php

Yii::import('application.models._base.BaseSysAudio');

class SysAudio extends BaseSysAudio
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('uid, name, uri, source_type', 'required'),
            array(
                'uri', 'file', 'types' => 'mp3,wav ',
                // 允许不上传！
                'allowEmpty' => false,
                'maxSize' => 1024 * 1024 * 50, // 50MB
                'tooLarge' => 'The file was larger than 50MB. Please upload a smaller file.',
                'minSize' => 1024 / 2,
                'tooSmall' => '文件太小了吧！至少大于0.5M哦！'
            ),

            array('name', 'default', 'value' => '未知歌手'),
            array('uid, play_order, listens, create_time, glean_count, file_size, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 120),
            array('singer', 'length', 'max' => 60),
            array('summary', 'length', 'max' => 500),
            array('uri', 'length', 'max' => 255),
            array('source_type', 'length', 'max' => 6),
            array('cmt_count', 'length', 'max' => 20),
            array('singer, summary, play_order, listens, cmt_count, glean_count, file_size, status', 'default', 'setOnEmpty' => true, 'value' => null),
            array('id, uid, name, singer, summary, uri, source_type, play_order, listens, create_time, cmt_count, glean_count, file_size, status', 'safe', 'on' => 'search'),
        );
    }

    const SOURCE_TYPE_LOCAL = 'local';
    const SOURCE_TYPE_REMOTE = 'remote';


    public static function getSourceTypeOptions()
    {
        return array(
            self::SOURCE_TYPE_LOCAL => '本地上传',
            self::SOURCE_TYPE_REMOTE => '网络歌曲',
        );
    }

    /**
     * @return array
     */
    public function relations(){
        return array(
            // 属于相册对象 由于是共用相册对象 所以id_object 是可能重复的 这里有必要做条件限制 id_album 从$_GET/$_POST 传递！：
            //  'albumObject'=>array(self::BELONGS_TO,'SysAlbumObject',array('id'=>'id_object'),'condition'=>'id_album=:id_album'),

            'albumObject'=>array(self::HAS_ONE,'SysAlbumObject',array('id_object'=>'id'),'condition'=>'id_album=:id_album'),
            'album'=>array(self::HAS_ONE,'SysAudioAlbum', array('id_album' => 'id'), 'through' => 'albumObject')
        );
    }

    protected function beforeDelete(){
        // 删除图片

        $deleteFileSuccess = YsUploadStorage::instance()->deleteFile($this->uri);

        return $deleteFileSuccess && parent::beforeDelete() ;
    }
    protected function afterDelete(){
        // 删除后需要更新相册信息：
       $album = $this->album ;
       if(!empty($album)){
           $album->obj_count = new CDbExpression('obj_count - 1');
           if($album->last_obj_id = $this->primaryKey ){
               // 如果最后一个对象id跟自己相等 那么清零
               $album->last_obj_id = 0 ;
           }
           $album->save(false);
       }

        $this->albumObject->delete();

        parent::afterDelete() ;

    }

    /**
     * @var int
     */
    public $albumId;

    protected function afterSave()
    {
        // 如果上传成功了那么数据存放到图片表去 同时桥表做关联
        if (!empty($this->albumId)) {
            if ($this->getIsNewRecord()) {
                $albumObj = DynamicActiveRecord::forTable('sys_album_object');
                $albumObj->id_album = $this->albumId;
                $albumObj->id_object = $this->primaryKey;
                // 在相册中的顺序
                $albumObj->obj_order = 0;
                $albumObj->save();

                // 更新相册成员数量
                $album = SysAudioAlbum::model()->findByPk($this->albumId);
                if ($album != null) {
                    $album->last_obj_id = $this->primaryKey ;
                    $album->obj_count = new CDbExpression('obj_count + 1');
                    if(!$album->save(false)){
                        // 其实都不应该要验证 直接存储 如果失败直接抛异常 在最外层用事务包裹就好了！
                       $this->addError('uri',print_r($album->getErrors(),true));
                    }
                }

            }
        }
        parent::afterSave();
    }
}