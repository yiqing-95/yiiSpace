<?php

Yii::import('photo.models._base.BasePhoto');

class Photo extends BasePhoto
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function afterSave()
    {
        parent::afterSave();
        PhotoAlbum::model()->updateCounters(array('mbr_count' => 1), 'id=:id', array(':id' => $this->album_id));
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            if (empty($this->hash)) {
                $this->hash = md5(microtime());
            }
        }
        return parent::beforeSave();
    }

    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
                      // 收藏
            'glean'=>array(self::BELONGS_TO, 'UserGlean', array('id'=>'object_id'),
                'condition' => 'object_type = :object_type ',
                'params' => array(':object_type' =>'photo')
            ),
        );
    }

    public function getThumbUrl()
    {
        return Ys::thumbUrl($this->path, 90, 90);
    }

    public function getViewUrl()
    {
        return bu($this->path);
    }

    /**
     * @return string the URL that shows the detail of the photo
     * ------------------------------------------------------------
     * 注意controller的createUrl 会考虑当前所处的module的 而直接用
     * app的该方法 不考虑moduleId 所以如果进行了URL规则设置 要小心这个
     * 东东！
     * ------------------------------------------------------------
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('photo/view', array(
            'id' => $this->id,
            'aid' => $this->album_id,
            'u' => $this->uid,
        ));
    }

    /**
     * @static
     * @param null $user
     * @param array $sqlDataProviderConfig
     * @return CSqlDataProvider
     */
    static public function listRecentPhotos($user = null, $sqlDataProviderConfig = array())
    {

        $cmd = Yii::app()->db->createCommand();
        $cmd->select("p.* , u.id as user_id , u.username ")
            ->from(" user u , user_profile up, photo p");
        //->leftJoin()

        if (empty($user)) {
            $cmd->where("u.id=up.user_id AND p.uid=u.id");
        } else {
            $cmd->where("u.id=up.user_id AND p.uid=u.id AND u.id=:uid", array(':uid' => $user));
        }
        $sql = $cmd->text;

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }

    static public function listCommendPhotos($sqlDataProviderConfig = array())
    {

        $cmd = Yii::app()->db->createCommand();
        $cmd->select("p.* , u.id as user_id , u.username ")
            ->from(" user u , user_profile up, photo p");
        //->leftJoin()

        $cmd->where("u.id=up.user_id AND p.uid=u.id");
        $sql = $cmd->text;

        $config = array();
        $config['totalItemCount'] = YiiUtil::countBySql($sql);
        $config['keyField'] = 'id';
        $config['sort'] = array(
            'defaultOrder' => 'id DESC',
            'attributes' => array(
                'id'
            )
        );
        $sqlDataProviderConfig = CMap::mergeArray($config, $sqlDataProviderConfig);
        return new CSqlDataProvider($sql, $sqlDataProviderConfig);
    }
}