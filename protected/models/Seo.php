<?php

Yii::import('application.models._base.BaseSeo');

/**
 * 多态SEO类 利用[Polymorphic relationships.](http://laravel.com/docs/eloquent#polymorphic-relations)
 *
 * 该技术来自lararel专家的博客
 * @see http://maxoffsky.com/code-blog/using-polymorphic-relationships-of-laravel-for-seo-content/
 * 思考 在yii中如何实现关系注入？  默认的实现方法都是在relations 方法中返回数组做死的是静态设计期弄的 在动态运行期
 * 如果能够注入关系 那么就可以不改变原有设计 无侵入的实现插件化的功能了！！
 *
 * 一般seo都是在view某个model时做表关联查询出seo信息 然后再把信息弄到<meta ...> 头部去
 * 还有一种ajax 拉取seo信息 然后js插入  但不知道这种做法是否影响搜索引擎？
 *
 * 实体类跟该类是一对一关系哦！ 在配置关系时还需要配一个seoble_type => get_class($this)
 *
 * Class Seo
 */
class Seo extends BaseSeo
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return parent::behaviors() + array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            )
        );
    }

    /**
     * note : 当验证属性只有一个时 切记不要出现空格： '  seoable_type','length',....会出错的 bug？？
     *
     * @return array
     */
    public function rules() {
        return array(
           //   array('title, description, keywords, seoble_id, seoble_type', 'required'),
           // 可以不输入就存一条么
            array('seoble_id,seoble_type', 'required'),
            array('seoble_type', 'length', 'max'=>255),
            array('title, description, keywords, seoble_type', 'length', 'max'=>255),
            array('seoble_id', 'length', 'max'=>11),
            array('id, title, description, keywords, seoble_id, seoble_type, created_at, updated_at', 'safe', 'on'=>'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => Yii::t('seo', 'id'),
            'title' => Yii::t('seo', 'seo title'),
            'description' => Yii::t('seo', 'seo description'),
            'keywords' => Yii::t('seo', 'seo keywords'),
            'seoble_id' => Yii::t('seo', 'seoble_id'),
            'seoble_type' => Yii::t('seo', 'seoble_type'),
            'created_at' => Yii::t('seo', 'created_at'),
            'updated_at' => Yii::t('seo', 'updated_at'),
        );
        /*
        return array(
            'id' => Yii::t('seo', 'id'),
            'title' => Yii::t('seo', 'title'),
            'description' => Yii::t('seo', 'description'),
            'keywords' => Yii::t('seo', 'keywords'),
            'seoble_id' => Yii::t('seo', 'seoble_id'),
            'seoble_type' => Yii::t('seo', 'seoble_type'),
            'created_at' => Yii::t('seo', 'created_at'),
            'updated_at' => Yii::t('seo', 'updated_at'),
        );
        */
    }
    /**
     * @return array customized attribute labels (name=>label)

    public function attributeLabels()
    {
        return array(
            'title' => $this->translator->translate('seo', 'Page title'),
            'keywords' => $this->translator->translate('seo', 'Page meta keywords'),
            'description' => $this->translator->translate('seo', 'Page meta description'),
        );
    }


     * @return CPhpMessageSource

    public function getTranslator()
    {
        if(!$this->_translator)
        {
            $this->_translator = new CPhpMessageSource();
            $this->_translator->basePath = Yii::getPathOfAlias('ext.YiiSEOBehavior.messages');
        }
        return $this->_translator;
    }
     *
     */

}