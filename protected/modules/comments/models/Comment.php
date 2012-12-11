<?php

/**
 * Comment class file.
 *
 * @author Dmitry Zasjadko <segoddnja@gmail.com>
 * @link https://github.com/segoddnja/ECommentable
 */
/**
 * Model, representing comment
 *
 * @version 1.0
 * @package Comments module
 */

/**
 *
 * The followings are the available columns in table '{{comments}}':
 * @property string $object_name
 * @property integer $object_id
 * @property integer $cmt_id
 * @property integer $cmt_parent_id
 * @property integer $author_id
 * @property string $user_name
 * @property string $user_email
 * @property string $cmt_text
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $status
 */
class Comment extends CActiveRecord {
    /*
     * Comment statuses
     */
    const STATUS_NOT_APPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DELETED = 2;

    /*
     * @var captcha code handler
     */

    public $verifyCode;

    /*
     * @var captcha action
     */
    public $captchaAction;
    
    /*
     * Holds current model config
     */
    private $_config;
    
    /*
     * Holds comments owner model
     */
    private $_ownerModel = false;
    
    private $_statuses = array(
        self::STATUS_NOT_APPROVED=>'New',
        self::STATUS_APPROVED=>'Approved',
        self::STATUS_DELETED=>'Deleted'
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Comments the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
       // return '{{comments}}';
        return 'tbl_comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        //get comments module
        $commentsModule = Yii::app()->getModule('comments');
        //get model config for comments module
        $modelConfig = $commentsModule->getModelConfig($this);
        $rules = array(
            array('object_name, object_id, cmt_text', 'required'),
            array('object_id, cmt_parent_id, author_id, create_time, update_time, status', 'numerical', 'integerOnly' => true),
            array('object_name', 'length', 'max' => 50),
            array('object_name, author_id, creator_name, user_name, user_email, verifyCode', 'checkConfig'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('object_name, object_id, cmt_id, cmt_parent_id, author_id, user_name, user_email, cmt_text, create_time, update_time, status', 'safe', 'on' => 'search'),
        );

        return $rules;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        $relations = array(
            'parent' => array(self::BELONGS_TO, 'Comment', 'cmt_parent_id'),
            'childs' => array(self::HAS_MANY, 'Comment', 'cmt_parent_id'),
        );
        $userConfig = Yii::app()->getModule('comments')->userConfig;
        //if defined in config class exists
        if (isset($userConfig['class']) && class_exists($userConfig['class'])) {
            $relations = array_merge($relations, array(
                'user' => array(self::BELONGS_TO, $userConfig['class'], 'author_id'),
            ));
        }
        return $relations;
    }

    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'object_name' => Yii::t('CommentsModule.msg', 'Commented object'),
            'object_id' => Yii::t('CommentsModule.msg', 'Commented object\'s ID'),
            /*'cmt_id' => 'Comment',
            'cmt_parent_id' => 'Parent Comment',
            'author_id' => 'Creator',*/
            'user_name' => Yii::t('CommentsModule.msg', 'User Name'),
            'user_email' => Yii::t('CommentsModule.msg', 'User Email'),
            'cmt_text' => Yii::t('CommentsModule.msg', 'Comment Text'),
            'create_time' => Yii::t('CommentsModule.msg', 'Create Time'),
            'update_time' => Yii::t('CommentsModule.msg', 'Update Time'),
            'status' => Yii::t('CommentsModule.msg', 'Status'),
            'verifyCode' => Yii::t('CommentsModule.msg', 'Verification Code'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('object_name', $this->object_name, true);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('cmt_id', $this->cmt_id);
        $criteria->compare('cmt_parent_id', $this->cmt_parent_id);
        $criteria->compare('author_id', $this->author_id);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('cmt_text', $this->cmt_text, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('t.status', $this->status);
        $relations = $this->relations();
        //if User model has been configured
        if(isset($relations['user']))
            $criteria->with = 'user';

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination'=>array(
                        'pageSize'=>30,
                    ),
                ));
    }
    
    /**
     * Checks config
     * This is the 'checkConfig' validator as declared in rules().
     */
    public function checkConfig($attribute,$params)
    {
        $config = $this->getConfig();
        //if object_name class exists in configuration
        if(count($config) === 0)
        {
            if($attribute === 'object_name')
                $this->addError ($attribute, Yii::t('CommentsModule.msg', 'This item cann\'t be commentable'));
                return;
        }
        //if only registered users can post comments
        if ($attribute === 'author_id' && ($config['registeredOnly'] === true || Yii::app()->user->isGuest === false))
        {
            unset($this->user_email, $this->user_name);
            $numberValidator = new CNumberValidator();
            $numberValidator->allowEmpty = false;
            $numberValidator->integerOnly = true;
            $numberValidator->attributes = array('author_id');
            $numberValidator->validate($this);
        }

        //if se captcha validation on posting
        if ($attribute === 'verifyCode' && $config['useCaptcha'] === true)
        {
            $captchaValidator = new CCaptchaValidator();
            $captchaValidator->caseSensitive = false;
            $captchaValidator->captchaAction = Yii::app()->urlManager->createUrl(CommentsModule::CAPTCHA_ACTION_ROUTE);
            $captchaValidator->allowEmpty = !CCaptcha::checkRequirements();
            $captchaValidator->attributes = array('verifyCode');
            $captchaValidator->validate($this);
        }

        //if not only registered users can post comments and current user is guest
        if (($attribute === 'user_name' || $attribute === 'user_email') && ($config['registeredOnly'] === false && Yii::app()->user->isGuest === true))
        {
            unset($this->author_id);
            $requiredValidator = new CRequiredValidator();
            $requiredValidator->attributes = array($attribute);
            $requiredValidator->validate($this);
            $stringValidator = new CStringValidator();
            $stringValidator->max = 128;
            $stringValidator->attributes = array($attribute);
            $stringValidator->validate($this);
            if($attribute === 'user_email')
            {
                $emailValidator = new CEmailValidator();
                $emailValidator->attributes = array('user_email');
                $emailValidator->validate($this);
            }
        }
    }

    /*
     * Return array with prepared comments for given modelName and id
     * @return Comment array array with comments 
     */

    public function getCommentsTree() {
        $config = $this->getConfig();

        $criteria = new CDbCriteria;
        $criteria->compare('object_name', $this->object_name);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('t.status', '<>'.self::STATUS_DELETED);
        $criteria->order = 't.cmt_parent_id, t.create_time ';
        if($config['orderComments'] === 'ASC' || $config['orderComments'] === 'DESC')
            $criteria->order .= $config['orderComments'];
        //if premoderation is seted and current user isn't superuser
        if($config['premoderate'] === true && $this->evaluateExpression($config['isSuperuser']) === false)
            $criteria->compare('t.status', self::STATUS_APPROVED);
        $relations = $this->relations();
        //if User model has been configured
        if(isset($relations['user']))
            $criteria->with = 'user';
        $comments = self::model()->findAll($criteria);
        return $this->buildTree($comments);
    }

    /**
     * @return CActiveDataProvider
     */
    public function  getTopLevelCmtDataProvider(){
        $config = $this->getConfig();

       // print_r($config) ; die(__METHOD__);
        $criteria = new CDbCriteria;
        $criteria->compare('object_name', $this->object_name);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('t.status', '<>'.self::STATUS_DELETED);
        //$criteria->order = 't.cmt_parent_id, t.create_time ';
        $criteria->order = 't.create_time ';
        if($config['orderComments'] === 'ASC' || $config['orderComments'] === 'DESC')
            $criteria->order .= $config['orderComments'];
        //if premoderation is seted and current user isn't superuser
        if($config['premoderate'] === true && $this->evaluateExpression($config['isSuperuser']) === false)
            $criteria->compare('t.status', self::STATUS_APPROVED);
        $relations = $this->relations();
        //if User model has been configured
        if(isset($relations['user']))
            $criteria->with = 'user';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>5,
            ),
        ));

    }

    public function beforeValidate() {
        if ($this->author_id === null && Yii::app()->user->isGuest === false)
            $this->author_id = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    /*
     * recursively build the comment tree for given root node
     * @param array $data array with comments data
     * @int $rootID root node id
     * @return Comment array 
     */

    private function buildTree(&$data, $rootID = 0) {
        $tree = array();
        foreach ($data as $id => $node) {
            $node->cmt_parent_id = $node->cmt_parent_id === null ? 0 : $node->cmt_parent_id;
            if ($node->cmt_parent_id == $rootID) {
                unset($data[$id]);
                $node->childs = $this->buildTree($data, $node->cmt_id);
                $tree[] = $node;
            }
        }
        return $tree;
    }

    /*
     * returns the string, which represents comment's creator
     * @return string 
     */

    public function getUserName() {
        $userName = '';
        if (isset($this->user)) {
            //if User model has been configured and comment posted by registered user
            $userConfig = Yii::app()->getModule('comments')->userConfig;
            $userName .= $this->user->$userConfig['nameProperty'];
            if (isset($userConfig['emailProperty']))
                $userName .= '(' . $this->user->$userConfig['emailProperty'] . ')';
        }
        else {
            $userName = $this->user_name . '(' . $this->user_email . ')';
        }
        return $userName;
    }
    
    /*
     * @return array
     */
    public function getConfig()
    {
        if($this->_config === null)
        {
            //get comments module
            $commentsModule = Yii::app()->getModule('comments');
            //get model config for comments module
            $this->_config = $commentsModule->getModelConfig($this->object_name);
        }
        return $this->_config;
    }
    

    /*
     * Set comment and all his childs as deleted
     * @return boolean
     */
    public function setDeleted()
    {
        /*todo add deleting for childs*/
        $this->status = self::STATUS_DELETED;
        return $this->update();
            
    }
    
    /*
     * Sets comment as approved
     * @return boolean
     */
    public function setApproved()
    {
        $this->status = self::STATUS_APPROVED;
        return $this->update();
            
    }
    
    /**
     * Get text representation of comment's status
     * @return string
     */
    public function getTextStatus()
    {
        $this->status = $this->status === null ? 0 : $this->status;
        return Yii::t('CommentsModule.msg', $this->_statuses[$this->status]);
    }
    
    /**
     * Generate data with statuses for dropDownList
     * @return array
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }
    
    /**
     * Get the link to page with this comment
     * @return string
     * 后台管理 评论时可以直接跳转到评论目标上 这时候需要
     * 一个链接 但评论作为独立模块 不应该依赖其他model（User除外）
     * 所以 每个objectName 的配置中需要一个能够跳到自己view页面的构造
     * -----------------------------------------------
     * 另一种方法是 仿照gridView中的CButtonColumn $xxxButtonUrl 做法。
     *
     * 已经注意到 嵌套性资源的访问 url会携带所有顶级路径上面的节点信息：
     * 如 yiiSpace/photo/view/id/22/aid/20/u/2#cmt-4
     *    但这些信息提前无法预知 通过某种静态方法或者callback也是可以算到 但仍旧麻烦。
     * 一种妥协做法： 每个可被评论的节点均提供简单访问URL形式：  yiiSpace/photo/view/$entityId
     * 这样就可以构造url表达式  使用 viewUrlExpression
     * 如: "/photo/view/$entityId"
     * 默认可用的 ：$objectName ,$objectId 就和你注册评论配置时的情况一致！这样可以使用yii的evaluateExpression
     * 来计算viewUrl的地址了！
     * --------------------------------------------------
     * 关于anchor 直接跳转到评论位置 基本上不大可能 ，由于使用了分页 所以跳到评论目标对象浏览页面
     * 就可以了  如果非要这么搞 那么可能用js方式 传递anchor 到客户端  比如 #cmt-40  这样评论id是40
     * 评论模块要完成这个工作：  先算出大于40评论总数 然后算这个页数  接着跳到这个页数去！！ 呵呵
     * ajax加载评论才有可能实现的
     */
    public function getPageUrl()
    {
        $config = $this->getConfig();
        //if isset settings for comments page url
        if(isset($config['pageUrl']) === true && is_array($config['pageUrl']) === true)
        {

            /**
            $ownerModel = $this->getOwnerModel();
            $routeData = array();
            foreach($config['pageUrl']['data'] as $routeVar=>$modelProperty)
                $routeData[$routeVar] = $ownerModel->$modelProperty;
            return Yii::app()->urlManager->createUrl($config['pageUrl']['route'], $routeData)."#comment-$this->cmt_id";
             * */
        }
        return null;
    }
    
    /*
     * Set comment status base on owner model configuration
     */
    public function beforeSave() {
        $config = $this->getConfig();
        //if current user is superuser, then automoderate comment and it's new comment
        if($this->isNewRecord === true && $this->evaluateExpression($config['isSuperuser']) === true)
            $this->status = self::STATUS_APPROVED;
        return parent::beforeSave();
    }

}