<?php
// Yii::import('blog.models.Comment');
/**
 * This is the model class for table "dlf_post".
 *
 * The followings are the available columns in table 'dlf_post':
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $summary
 * @property string $tags
 * @property int $status
 * @property int $created
 * @property int $updated
 * @property int $author_id
 * @property int $category_id
 */
class Post extends CActiveRecord
{


    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;
    public $year;
    public $month;
    public $posts = 0;


    private $_oldTags;

    /**
     * @var array
     */
    public $sysCategories = array();

    /**
     * Returns the static model of the specified AR class.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return BlogModule::getDbTablePrefix() . 'post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, content, summary, status', 'required'),
            array('title', 'length', 'max' => 128),
            array('summary', 'length', 'max' => 255),
            array('status, created, updated, author_id, category_id', 'length', 'max' => 11),
            array('tags,sysCategories', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, content, summary, tags, status, created, updated, author_id, category_id,recommendGrade', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors()
    {
        return array(
            'activerecord-relation' => array(
                'class' => 'ext.yiiext.behaviors.activerecord-relation.EActiveRecordRelationBehavior',
            ),
            // 可以直接写类名 已经在配置文件中导入了整个文件夹
            'searchable' => array(
                'class' => 'ext.Yii-Elastica.components.ElasticArBehavior',
            ),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
          //  'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition' => 'comments.status=' . Comment::STATUS_APPROVED, 'order' => 'comments.created DESC'),
          //  'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition' => 'status=' . Comment::STATUS_APPROVED),

            'sysCates' => array(self::MANY_MANY, 'BlogSysCategory', 'blog_sys_category2post(post_id,sys_cate_id)'),

            // 收藏
            'glean'=>array(self::BELONGS_TO, 'UserGlean', array('id'=>'object_id'),
                'condition' => 'object_type = :object_type ',
                'params' => array(':object_type' =>'blog')
            ),

            'seo'=>array(
                self::HAS_ONE ,'Seo','seoble_id',
                'on' => 'seo.seoble_type = :seoble_type ',
                'params' => array(':seoble_type' =>get_class($this))
            )
        );
    }

    /**
     * 返回改对象的所有者id
     * 所有的用户内容都提供这个方法 这样可以拷贝代码！！
     * @return string
     */
    public function getOwnerId(){
        return $this->author_id ;
    }

    public function recent($limit = 3)
    {

        $this->getDbCriteria()->mergeWith(array(
            //'order' => $this->getTableAlias(false, false).'.createTime DESC',
            'limit' => (int)$limit,
        ));

        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'summary' => 'Summary',
            'tags' => 'Tags',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
            'author_id' => 'Author',
            'category_id' => 'Category',
            'sysCategories' => '系统分类'
        );
    }

    public function findEditorsPicks($limit = 10)
    {
        return $this->findAll(array(
            'condition' => 't.status=' . self::STATUS_PUBLISHED,
            'order' => 't.created DESC',
            'limit' => $limit,
        ));
    }

    public function findArchives($uid = -1)
    {
        if ($uid != -1) {
            return $this->findAll(array(
                'select' => 'YEAR(FROM_UNIXTIME(created)) AS `year`, MONTH(FROM_UNIXTIME(created)) AS `month`, count(id) as posts',
                'condition' => 't.status=:status AND author_id=:uid',
                'params' => array(
                    ':status' => self::STATUS_PUBLISHED,
                    ':uid' => $uid,
                ),
                'group' => 'YEAR(FROM_UNIXTIME(created)), MONTH(FROM_UNIXTIME(created))',
                'order' => 't.created DESC',
            ));


        } else {
            return $this->findAll(array(
                'select' => 'YEAR(FROM_UNIXTIME(created)) AS `year`, MONTH(FROM_UNIXTIME(created)) AS `month`, count(id) as posts',
                'condition' => 't.status=' . self::STATUS_PUBLISHED,
                'group' => 'YEAR(FROM_UNIXTIME(created)), MONTH(FROM_UNIXTIME(created))',
                'order' => 't.created DESC',
            ));
        }
    }

    /**
     * @return string the URL that shows the detail of the post
     */
    public function getUrl()
    {
        return Yii::app()->controller->createUrl('post/view', array(
            'id' => $this->id,
            'title' => str_replace(' ', '-', trim($this->title)),
        ));
    }

    /**
     * 这个功能最好移到助手类去 不然依赖倒置了
     * 模型层不要依赖控制器层！
     *
     * @param $id
     * @param $title
     * @return string
     */
    public static  function createPostViewUrl($id,$title){
        return Yii::app()->controller->createUrl('post/view', array(
            'id' => $id,
            'title' => str_replace(' ', '-', trim($title)),
        ));
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagLinks()
    {
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = CHtml::link(CHtml::encode($tag), array('post/index', 'tag' => $tag));
        return $links;
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    /**
     * Adds a new comment to this post.
     * This method will set status and post_id of the comment accordingly.
     * @param Comment the comment to be added
     * @return boolean whether the comment is saved successfully
     */
    public function addComment($comment)
    {
        if (Yii::app()->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        $comment->post_id = $this->id;
        return $comment->save();
    }

    /**
     * @var array
     * old attributes for current model
     */
    protected $old = array();

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    protected function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;

        $this->old = $this->attributes;
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->created = $this->updated = time();
                $this->author_id = Yii::app()->user->id;
            } else
                $this->updated = time();
            return true;
        } else
            return false;
    }

    /**
     * This is invoked after the record is saved.
     */
    protected function afterSave()
    {
        parent::afterSave();
        Tag::model()->updateFrequency($this->_oldTags, $this->tags);

        if ($this->getIsNewRecord()) {
            Category::model()->updateCounters(array('mbr_count' => 1), 'id=:cate', array(':cate' => $this->category_id));
            //  状态墙 这里以后可能引入队列或者异步
            $statusData = array(
                'id' => $this->primaryKey,
                'title' => $this->title,
                'teaser' => $this->summary,
            );
            /**
             *   $statusModel->creator = $creator ;
             * $statusModel->type = $type ;
             * $statusModel->profile = $profile;
             * $statusModel->update = $update ;
             * $statusModel->created = $created ;
             * $statusModel->approved = $approved ;
             */
            $status = array(
                'creator' => $this->author_id,
                'type' => 'blog_create',
                'profile' => $this->author_id,
                'update' => CJSON::encode($statusData),
                //  'created'=>time(),
                'approved' => 1
            );
            YsService::call('status', 'postStatus', array($status));

        } else {
            if ($this->category_id != $this->old['category_id']) {
                Category::model()->updateCounters(array('mbr_count' => 1), 'id=:cate', array(':cate' => $this->category_id));
                Category::model()->updateCounters(array('mbr_count' => -1), 'id=:cate', array(':cate' => $this->old['category_id']));
            }

        }
    }

    /**
     * This is invoked after the record is deleted.
     */
    protected function afterDelete()
    {
        parent::afterDelete();
    //    Comment::model()->deleteAll('post_id=' . $this->id);
        Tag::model()->updateFrequency($this->tags, '');
        Category::model()->updateCounters(array('mbr_count' => -1), 'id=:cate', array(':cate' => $this->category_id));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('summary', $this->summary, true);
        $criteria->compare('tags', $this->tags, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('updated', $this->updated, true);
        $criteria->compare('author_id', $this->author_id, true);
        $criteria->compare('category_id', $this->category_id, true);
        return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 'created DESC', //设置默认排序是created倒序
            ),
            'criteria' => $criteria,
        ));
    }

    /**
     * @var int
     * 推荐等级
     */
    public $recommendGrade;
    /**
     * @var int
     * 推荐id  把推荐表的东西移到这里了
     */
    public $recommendId;

    //--------------------------------------------------------\\
    // 搜索相关的支持

    /**
     * 里面的有些方法来自ElasticaArBehavior里面哦
     * 不要感到惊奇
     * @return \Elastica\Type
     */
    public function getEsType(){
        // $client = new \Elastica\Client();
        $client = $this->getElasticConnection()->getClient();
        $index = $client->getIndex($this->getElasticIndex());
        $type = $index->getType($this->getElasticType());
        return $type ;
    }

    /**
     * @return \Elastica\ResultSet|array
     */
    public function moreLikeThis(){
        $mltQuery = new \Elastica\Query\MoreLikeThis();
        $mltQuery->setLikeText($this->title);
        $mltQuery->setFields(array('title','summary'));
        $mltQuery->setMaxQueryTerms(1);
        $mltQuery->setMinDocFrequency(1);
        $mltQuery->setMinTermFrequency(1);

        $query = new \Elastica\Query();
       //  $query->setFields(array('title','summary',''));
        $query->setFields(array('id', 'title','summary'));
        $query->setQuery($mltQuery);

        $type = $this->getEsType() ;
        $resultSet = $type->search($query);
        //  $resultSet->count()
       // var_dump($resultSet);die();

        return $resultSet ;
        // $resultSet->getResults()[0]->getSource()
        // return  $resultSet->getResponse()->getData();

    }

    //--------------------------------------------------------//
}