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
        //if object_name class exists in configuration
        if(count($this->config) === 0)
        {
            if($attribute === 'object_name')
                $this->addError ($attribute, Yii::t('CommentsModule.msg', 'This item cann\'t be commentable'));
                return;
        }
        //if only registered users can post comments
        if ($attribute === 'author_id' && ($this->config['registeredOnly'] === true || Yii::app()->user->isGuest === false))
        {
            unset($this->user_email, $this->user_name);
            $numberValidator = new CNumberValidator();
            $numberValidator->allowEmpty = false;
            $numberValidator->integerOnly = true;
            $numberValidator->attributes = array('author_id');
            $numberValidator->validate($this);
        }

        //if se captcha validation on posting
        if ($attribute === 'verifyCode' && $this->config['useCaptcha'] === true)
        {
            $captchaValidator = new CCaptchaValidator();
            $captchaValidator->caseSensitive = false;
            $captchaValidator->captchaAction = Yii::app()->urlManager->createUrl(CommentsModule::CAPTCHA_ACTION_ROUTE);
            $captchaValidator->allowEmpty = !CCaptcha::checkRequirements();
            $captchaValidator->attributes = array('verifyCode');
            $captchaValidator->validate($this);
        }

        //if not only registered users can post comments and current user is guest
        if (($attribute === 'user_name' || $attribute === 'user_email') && ($this->config['registeredOnly'] === false && Yii::app()->user->isGuest === true))
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
        $criteria = new CDbCriteria;
        $criteria->compare('object_name', $this->object_name);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('t.status', '<>'.self::STATUS_DELETED);
        $criteria->order = 't.cmt_parent_id, t.create_time ';
        if($this->config['orderComments'] === 'ASC' || $this->config['orderComments'] === 'DESC')
            $criteria->order .= $this->config['orderComments'];
        //if premoderation is seted and current user isn't superuser
        if($this->config['premoderate'] === true && $this->evaluateExpression($this->config['isSuperuser']) === false)
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
        $criteria = new CDbCriteria;
        $criteria->compare('object_name', $this->object_name);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('t.status', '<>'.self::STATUS_DELETED);
        //$criteria->order = 't.cmt_parent_id, t.create_time ';
        $criteria->order = 't.create_time ';
        if($this->config['orderComments'] === 'ASC' || $this->config['orderComments'] === 'DESC')
            $criteria->order .= $this->config['orderComments'];
        //if premoderation is seted and current user isn't superuser
        if($this->config['premoderate'] === true && $this->evaluateExpression($this->config['isSuperuser']) === false)
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
     * Returns comments owner model
     * @return CActiveRecord $model
     */
    public function getOwnerModel()
    {
        if($this->_ownerModel === false)
        {
            if(is_array($primaryKey = $this->primaryKey()) === false)
                $key = $this->object_id;
            else
                $key = array_combine($primaryKey, explode('.', $this->object_id));
            $ownerModel = $this->object_name;

            if(class_exists($ownerModel))
		$this->_ownerModel = CActiveRecord::model($ownerModel)->findByPk($key);
            else 
                $this->_ownerModel = null;
        }
        return $this->_ownerModel;
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
     */
    public function getPageUrl()
    {
        $config = $this->config;
        //if isset settings for comments page url
        if(isset($config['pageUrl']) === true && is_array($config['pageUrl']) === true)
        {
            $ownerModel = $this->getOwnerModel();
            $routeData = array();
            foreach($config['pageUrl']['data'] as $routeVar=>$modelProperty)
                $routeData[$routeVar] = $ownerModel->$modelProperty;
            return Yii::app()->urlManager->createUrl($config['pageUrl']['route'], $routeData)."#comment-$this->cmt_id";
        }
        return null;
    }
    
    /*
     * Set comment status base on owner model configuration
     */
    public function beforeSave() {
        //if current user is superuser, then automoderate comment and it's new comment
        if($this->isNewRecord === true && $this->evaluateExpression($this->config['isSuperuser']) === true)
            $this->status = self::STATUS_APPROVED;
        return parent::beforeSave();
    }

}