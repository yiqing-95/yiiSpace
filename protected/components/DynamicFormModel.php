<?php
/**
用法：
 * 控制器中：
 *
$formConfig =  array(
'title'=>'Please provide your login credential',

'elements'=>array(
'username'=>array(
'type'=>'text',
'maxlength'=>32,
),
'password'=>array(
'type'=>'password',
'maxlength'=>32,
),
'rememberMe'=>array(
'type'=>'checkbox',
)
),

'buttons'=>array(
'login'=>array(
'type'=>'submit',
'label'=>'Login',
),
),
);

$formConfig = array(
'elements'=>array(
'firstname'=>array(
'type'=>'text',
'maxlength'=>40,
),
'lastname'=>array(
'type'=>'text',
'maxlength'=>40,
),
'membersince'=>array(
'type'=>'dropdownlist',
//it is important to add an empty item because of new records
'items'=>array(''=>'-',2009=>2009,2010=>2010,2011=>2011,),
),
),
'buttons'=>array(
'submit'=>array('type'=>'submit','label'=>'提交'),
),
);

$form = new  DynamicFormModel ;//LoginForm;
$form->setAttributeNames(array_keys($formConfig['elements']));

$form = new CForm($formConfig, $form);

if(isset($_POST['DynamicForm'])) {
$model->attributes = $_POST['DynamicForm'];
print_r($model->getModelData());
//print_r($_POST);
}

$this->render('dynamicForm', array('form'=>$form));


视图中：
<div class="form">
<?php
echo $form; ?>
</div>
 *
 *
 *
 *
 *
 *
 */

/**
 * 动态表单模型 多个表单配置可以共用同一个表单模型
 * 需要一个表单配置数组或者文件（其中存放的是表单配置）
 *
 *
 * -------------------------------------------------
 * 可能需要复写：setAttributes 方法 来为模型赋值
 * 同时复写魔术方法 __set()  有时间了做
 * -------------------------------------------------
 * 2011/7/27 0:38 add:
 * 可以使用 addRule  动态添加一个验证规则
 *
 * 可以使用 $model->attributes = array('key'=>$value..'keyX'=>$valueX.....);
 *
 * 注意设置验证规则：一定要在 $model->attributes = $_POST['DynamicFormModel'];
 * 之前完成 ，总之以前是 静态设计部分的内容 现在均改为动态的了
 *
 * 所有的操作尽可能一实例化就做 。比如设置label 设置 attribute
 *
 * 唯独 $model->attributes = $someArray 往后推
 *
 * ---------------------------------------------------
 */
class DynamicFormModel extends CFormModel
{
    //...................................................

    /**
     * @static
     * @param string $modelClass 你需要模型名字是什么
     * @param string $scenario 场景 用来归类验证规则的
     * @return DynamicFormModel 的子类
     * 动态生成了一个类 该类继承自DynamicFormModel 在往上的父类是CFormModel
     * 注意使用了eval
     */
    public static function &createFormModel($modelClass = '', $scenario = '')
    {

        if (strcasecmp($modelClass, __CLASS__) === 0) {
            //类名跟本类相等
            $modelClass = __CLASS__;
        } elseif (!class_exists($modelClass, false)) {
            eval('class ' . $modelClass . ' extends DynamicFormModel { }');
        }


        $model = new  $modelClass($scenario); //LoginForm;
        return $model;
    }

    /**
     * @static
     * @param array $formElementConfig
     * @param string $modelName
     * @return void
     * ----------------------------------------
     * 根据单个表单元素的配置渲染输出
     * 根据内部实现 这个输出应该是很费资源的
     * 所以尽量少用 或者只是为了调试。
     * ----------------------------------------
     */
    public static function renderFormElements($formElementConfig = array(), $modelName = __CLASS__)
    {
        $formModel = self::createFormModel($modelName);
        $formModel->setAttributeNames(array_keys($formElementConfig));

        $form = new CForm(array('elements' => $formElementConfig), $formModel);

        //没有 activeForm 就不能提取元素的子 所以不得已 先抛弃一些内融
        // CVarDumper::dump($formModel);

        $form->renderBegin(); //只是字符串而已 不输出没啥问题  但不调用这个方法就不能实例化$_activeForm

        $outPut = '';
        foreach ($form->getElements() as $element) {
            $outPut .= $element->render();
        }
        $form->renderEnd(); //同renderBegin方法一样 幌子而已 看实现
        return $outPut;


    }

    /**
     * @static
     * @throws CException
     * @param $config
     * @param string $modelName
     * @param string $scenario
     * @return CForm
     * 根据配置文件渲染表单
     */
    public static function renderForm($config, $modelName = 'Test', $scenario = '')
    {

        if (is_string($config))
            $config = require(Yii::getPathOfAlias($config) . '.php');
        if (!is_array($config)) {
            throw new CException('config must be an  array  or config file which is a path alias ! ');
        }

        //$formModel = self::createFormModel($modelName);
        $formModel = new self($scenario);
        $formModel->setAttributeNames(array_keys($config['elements']));
        $form = new CForm($config, $formModel);
        //还需要一个替换  就是表单名 有空做
        $form = str_replace(array(__CLASS__ . '_', __CLASS__ . '['), array($modelName . '_', $modelName . '['), '' . $form);

        return $form;
    }

    //=========<原始实现 此节内容可在必要时修改掉 估计需要改掉的是attributeNames()方法的实现>==============================================================
    private static $_names = array();

    /**
     * Constructor.
     * @param string $scenario name of the scenario that this model is used in.
     * See {@link CModel::scenario} on how scenario is used by models.
     * @see getScenario
     */
    public function __construct($scenario = '')
    {
        $this->setScenario($scenario);
        $this->init();
        $this->attachBehaviors($this->behaviors());
        $this->afterConstruct();
    }

    /**
     * Initializes this model.
     * This method is invoked in the constructor right after {@link scenario} is set.
     * You may override this method to provide code that is needed to initialize the model (e.g. setting
     * initial property values.)
     * @since 1.0.8
     */
    public function init()
    {
    }

    /**
     * @static
     * @param $name
     * @return string
     * used to generate the form id
     */
    protected static function class2id($name)
    {
        return trim(strtolower(str_replace('_', '-', preg_replace('/(?<![A-Z])[A-Z]/', '-\0', $name))), '-');
    }

    /**
     * @static
     * @param string $className
     * @return string
     * return  the form id according to class name;
     */
    static public function  getFormId($className = __CLASS__)
    {
        return self::class2id($className) . '-form';
    }

    /**
     * @param null $model
     * @param null $form
     */
    public function performAjaxValidation($model = null, $form = null)
    {
        $model = empty($model) ? $this : $model;
        $form = empty($form) ? self::getFormId() : $form;

        if (Yii::app()->request->isAjaxRequest && (isset($_POST['ajax']) && $_POST['ajax'] == $form)) {
            echo GxActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Returns the list of attribute names.
     * By default, this method returns all public properties of the class.
     * You may override this method to change the default.
     * @return array list of attribute names. Defaults to all public properties of the class.
     */
    public function attributeNames()
    {
        $className = get_class($this);
        if (!isset(self::$_names[$className])) {
            $names = array();

            $attributeNames = array_keys($this->_modelData);
            foreach ($attributeNames as $attributeName)
            {

                $names[] = $attributeName;
            }
            return self::$_names[$className] = $names;
        }
        else
            return self::$_names[$className];
        /*
         * 原码实现在下面
         * --------------------------------------------
            $className=get_class($this);
            if(!isset(self::$_names[$className]))
            {
                    $class=new ReflectionClass(get_class($this));
                    $names=array();
                    foreach($class->getProperties() as $property)
                    {
                            $name=$property->getName();
                            if($property->isPublic() && !$property->isStatic())
                                    $names[]=$name;
                    }
                    return self::$_names[$className]=$names;
            }
            else
                    return self::$_names[$className];
        */
    }

    //.....................................................................................
    /**
     * Returns all attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes as listed in {@link attributeNames} will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @return array attribute values (name=>value).
     */
    public function getAttributes($names = null)
    {
        $values = array();
        foreach ($this->attributeNames() as $name)
            $values[$name] = $this->$name;

        if (is_array($names)) {
            $values2 = array();
            foreach ($names as $name)
                $values2[$name] = isset($values[$name]) ? $values[$name] : null;
            return $values2;
        }
        else
            return $values;
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current {@link scenario}.
     * @see getSafeAttributeNames
     * @see attributeNames
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (!is_array($values))
            return;

        //........................<<修改的地方..............................
        $this->setAttributeNames(array_keys($values));

        //........................<<修改的地方/>>..............................

        $attributes = array_flip($safeOnly ? $this->getSafeAttributeNames() : $this->attributeNames());
        foreach ($values as $name => $value)
        {
            if (isset($attributes[$name]))
                $this->$name = $value;
            else if ($safeOnly)
                $this->onUnsafeAttribute($name, $value);
        }
    }


    //=========<原始实现 此节内容是原始类中的代码/>==============================================================
    /**
     * @var array
     * 模型数据
     */
    protected $_modelData = array();


    /**
     * @var array
     * 验证规则
     */
    protected $_rules = array();


    /**
     * @var array
     * 属性标签
     */
    protected $_attributeLabels = array();


    /**
     * @param array $attributeNames
     * @return void
     * 设置表单的属性名
     */
    public function  setAttributeNames(array $attributeNames)
    {
        // print_r($attributeNames); die;
        foreach ($attributeNames as $name)
            $this->addAttributeName($name);
    }

    public function addAttributeName($name)
    {
        $attibuteNames = explode(',', $name);
        foreach ($attibuteNames as $attrName) {
            $attrName = trim($attrName);
            if (!array_key_exists($attrName, $this->_modelData))
                $this->_modelData[$attrName] = null;
        }
    }

    //...........................................................................................
    /*
     * 模拟softDocument 的思路
	public function initSoftAttribute($name)
	{
		if(!array_key_exists($name, $this->_attributeNames))
			$this->_attributeNames[$name] = null;
	}

	public function initSoftAttributes($attributes)
	{
		foreach($attributes as $name)
			$this->initSoftAttribute($name);
	}
    */
    //...........................................................................................

    /**
     * @param array $modelData
     * @return void
     * 为 模型设置值 键值对形式
     * array(
     *    'key1' => $value .....
     * );
     *
     * -------------------------------------------
     * 此方法语义跟 setAttribute 一样 或许需要复写那个方法
     *
     * -------------------------------------------
     */
    public function setModelData(array $modelData)
    {
        $this->_modelData = $modelData;
    }

    /**
     * @return array
     * 返回表单数据
     *-------------------------------
     * 在块赋值后可以获取到赋值了的数据：
     * 不过貌似没有必要了 这个返回的是
     * 数组 跟$this->attributes 估计同义
     *
     */
    public function  getModelData()
    {
        return $this->_modelData;
    }

    /**
     * @param array $rules
     * @return void
     * ----------------------------------
     * 设置验证规则 格式如CModel::rules()中
     * 的东西
     * -----------------------------------
     */
    public function  setRules(array $rules)
    {
        // $this->_rules = $rules;
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * @return array
     * 获取设置的规则
     * 只是为_rules 提供getter/setter 而已
     */
    public function getRules()
    {
        return $this->_rules;
    }

    public function  addRule($validateRule)
    {
        if (is_array($validateRule) && !property_exists($this, $validateRule[0])) {
            $this->addAttributeName($validateRule[0]);
        }
        $this->_rules[] = $validateRule;
    }

    /**
     * @return array
     * 如果验证规则没有设置那么表单不会显示出改字段单元的
     *
     */
    public function rules()
    {
        $attributeNames = array_keys($this->_modelData);

        // $safeAttributeNames = array(implode(', ', $attributeNames), 'safe');
        /**
         * 如果设置了验证规则就用 设置的
         * 如果没设置那么 所有的属性被认为是安全的
         */
        if (!empty($this->_rules)) {
            /**
             * 没加入验证规则的认为是安全属性
             * 原先实现只是这个 return $this->_rules;
             */
            if (!empty($attributeNames)) {
                $this->_rules[] = array(implode(', ', $attributeNames), 'safe');
            }
            return $this->_rules;
            //var_dump($this->_rules); die;
            //
        } elseif (!empty($attributeNames)) {
            return array(array(implode(', ', $attributeNames), 'safe'),
            );
        } else {
            return array();
        }
    }

    /**
     * @param array $attributeLabels
     * @return void
     * ------------------------
     * 设置属性标签显示值
     * key是属性名称  value是将要显示在views
     * 中的label值
     * ------------------------
     */
    public function  setAttributeLabels(array $attributeLabels)
    {
        $this->_attributeLabels = $attributeLabels;

        return $this;
    }

    /**
     * @param $attributeName
     * @param null $attributeLabel
     * @return \DynamicFormModel
     */
    public function setAttributeLabel($attributeName, $attributeLabel = null)
    {
        $this->_attributeLabels[$attributeName] = is_null($attributeLabel) ? $attributeName : $attributeLabel;
        return $this;
    }

    /**
     * @return array
     *
     */
    public function attributeLabels()
    {
        return $this->_attributeLabels;
        //return array_combine($this->_attributeNames,$this->_attributeNames);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * --------------------------------------
     * 设置对象的此属性值
     * -------------------------------------
     */
    public function  __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (CException $ex) {
            /**
             * 这个地方其实还需要安全判断一下 是否是attributeNames的成员之一
             * 或者判断一下 是否......
             */
            $this->_modelData[$name] = $value;
            //无法调用父类的同名方法 ！；
        }
    }

    /**
     * @param $name
     * @return mixed
     * -----------------------------------------
     * 获取指定attribute 的对象值
     * 可以在这里弄默认值 呵呵
     * -----------------------------------------
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_modelData)) {
            return $this->_modelData[$name];
        } else {
            // print_r($this->_modelData);
            return parent::__get($name);
        }
    }

    public function __isset($name)
    {
        if (array_key_exists($name, $this->_modelData)) // Use of array_key_exists is mandatory !!!
            return true;
        else
            return parent::__isset($name);
    }

    public function __unset($name)
    {
        if (array_key_exists($name, $this->_modelData)) // Use of array_key_exists is mandatory !!!
            unset($this->_modelData[$name]);
        else
            parent::__unset($name);
    }

    public static function __callStatic($name, $arguments)
    {
        if (strcasecmp($name, 'renderFormElement') === 0) {
            return call_user_func_array(array(__CLASS__, 'renderFormElements'), $arguments);
        }
    }

    //............<flash 上传文件的支持>.............................................................................
    private $_filePostName;

    /**
     * @param string $filePostName
     * @return DynamicFormModel
     */
    public function  setFilePostName($filePostName = 'Filedata')
    {
        $this->_filePostName = $filePostName;
        return $this;
    }

    /**
     * @return string
     * 返回文件上传字段的名字
     */
    public function getFilePostName()
    {
        return $this->_filePostName;
    }

    private $_modelClassName;

    /**
     * @param string $modelName
     * @return DynamicFormModel
     */
    public function setModelClassName($modelName = '')
    {
        $this->_modelClassName = $modelName;
        return $this;
    }

    /**
     * @var string
     * 上传文件的原始名称
     */
    private $_originalUploadedFileName = '';

    /**
     * @return string
     * 获取上传文件的原始名字
     */
    public function getOriginalUpFileName()
    {
        return $this->_originalUploadedFileName;
    }


    /**
     * @var string
     */
    protected $_destFilePath;

    /**
     * @return string
     * 获取最终文件上传后的路径地址
     */
    public function getDestinationFilePath()
    {
        return $this->_destFilePath;
    }

    /**
     * @throws CException
     * @param $destinationPath
     * @param bool $preserveOriginalExtension 是否保留原有扩展作为新文件的名字
     * @return bool
     * --------------------------------------------
     * 关于错误信息的转换 ajax验证时返回的错误信息
     * 格式时 activeId => error;
     * --------------------------------------------
     */
    public function processUpload($destinationPath, $preserveOriginalExtension = false)
    {

        // throw new CException('to be continue!');
        if (empty($this->_filePostName)) {
            throw new CException('must give a filePostName');
        }


        //$this->addRule(array($this->_filePostName, 'file', 'allowEmpty' => false));
        /**
         *        //数组转储：转为模型名是自己的类名，属性名为filePostName所传递的 如果是数组结构的文件字段名 Photo[path] 那么结果
         * 等价于 DynamicFormMedel[path] 在$_FILES 数组中的结构
         */

        $this->copyFilesArray();

        // print_r($_FILES);

        if (preg_match('/([^\[]+)\[(\w+)\]/', $this->_filePostName, $matches)) {
            // print_r($matches);
            $filePostName = $matches[2];

            $this->{$matches[2]} = array($matches[2] => '');

            $this->{$matches[2]} = CUploadedFile::getInstance($this, $matches[2]);
        } else {
            $filePostName = $this->_filePostName;
            $this->addAttributeName($filePostName);

            $this->{$this->_filePostName} = CUploadedFile::getInstance($this, $this->_filePostName);
            // $this->{$this->_filePostName} = CUploadedFile::getInstanceByName($this->_filePostName);
            // echo __LINE__, print_r($this->{$this->_filePostName});
        }

        //设置上传文件的原始路径
        $this->_originalUploadedFileName = $this->{$filePostName}->getName();

        if ($this->validate()) {
            //如果保留原始扩展名：
            if ($preserveOriginalExtension == true) {
                $ext = $this->{$this->_filePostName}->getExtensionName();
                $destinationPath = $destinationPath . '.' . $ext;
            }

            $this->_destFilePath = $destinationPath;
            //验证通过了就处理上传
            $this->{$filePostName}->saveAs($destinationPath);

            return true;
        } else {
            //修改错误信息：
            // $this->_error;

            return false;
        }

    }

    //..............<获取ajax错误格式>...................................
    /**
     * @return string
     * 返回用户ajax验证的错误形式
     */
    public function  getErrorsAsJson($modelClass = __CLASS__)
    {
        $result = array();

        foreach ($this->getErrors() as $attribute => $errors) {
            $result[CHtml::activeId($this, $attribute)] = $errors;
        }

        $result = function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);


        if ($modelClass !== __CLASS__) {
            $result = str_replace(__CLASS__, $modelClass, $result);
        }
        return $result;
    }

    //..............<获取ajax错误格式/>...................................
    /**
     * @return void
     * 拷贝 $_FILES 数组
     * 真想说脏话 算了还是忍忍吧
     */
    public function copyFilesArray()
    {

        if (strpos($this->_filePostName, '[') === false) {
            // echo "whann";
            $attribute = $this->_filePostName;
            //没找到 数组形式的文件域字段名
            $_FILES[__CLASS__] = array(
                'name' => array($attribute => $_FILES[$this->_filePostName]['name']),
                'type' => array($attribute => $_FILES[$this->_filePostName]['type']),
                'tmp_name' => array($attribute => $_FILES[$this->_filePostName]['tmp_name']),
                'error' => array($attribute => $_FILES[$this->_filePostName]['error']),
                'size' => array($attribute => $_FILES[$this->_filePostName]['size']),
            );
        } else {
            /**
             *
             * 麻烦了 要正则抓取  如User[icon] 抓取其中的User 和icon
             * 然后用下面的方法来完成替换：
             *
             * $mathes[0] 是全匹配
             * $matches[1] 是小括号中第一个子模式的匹配
             * $matches[2] 是第二个子模式的匹配 这里就是中括号中的东西
             *
             **/
            preg_match('/([^\[]+)\[(\w+)\]/', $this->_filePostName, $matches);
            //print_r($matches);
            $_FILES = $this->changeKeyNameRecursive(array($matches[1] => __CLASS__), $_FILES);
        }
    }

    /**
     * @param array $originalKey_newKey
     * @param $array
     * @return array
     * 递归替换掉 键一位新键
     */
    private function changeKeyNameRecursive(array $originalKey_newKey, &$array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $array[$k] = self::changeKeyNameRecursive($originalKey_newKey, $v);
            }
            if (isset($originalKey_newKey[$k])) {
                $array[$originalKey_newKey[$k]] = $array[$k];
                unset($array[$k]);
            }
        }
        return $array;
    }

    //............<flash 上传文件的支持/>.............................................................................


}
/**
 * 补充一些用法：
 *  $dm = new DynamicFormModel();
$dm->addRule(array(
'res_path', 'file', 'types' => 'exe',// 'jpg, gif, png',
'maxSize' => 1024 * 1024 * 50, // 50MB
'tooLarge' => 'The file was larger than 50MB. Please upload a smaller file.',
'minSize' => 1024*1024,
'tooSmall' => '文件太小了吧！至少大于0.5M哦！'
)

);
$dm->addRule(array(
'res_path',
'ext.validation.EPictureValidator',
'minWidth' => 20,
'minHeight' => 20,
)
);
$dm->setFilePostName('MediaRes[res_path]');

if($dm->processUpload('./data/tmp/hi.jpg')){

}else{
// ArrayUtil::dumpArray($dm->getErrors());
echo $dm->getErrorsAsJson('User');
}
ArrayUtil::dumpArray($_FILES);
die;

}
 *
 *
 *
 */