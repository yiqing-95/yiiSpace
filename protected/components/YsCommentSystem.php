<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-12-10
 * Time: 下午7:36
 * To change this template use File | Settings | File Templates.
 */
class YsCommentSystem
{


    /**
     * @static
     * @return array|mixed
     */
    public static function getAllSystems()
    {
        $cacheKey = __METHOD__;
        $systems = Yii::app()->cache->get($cacheKey);
        if ($systems === false) {
            $db = Yii::app()->db;
            $cmd = $db->createCommand('SELECT * FROM `sys_object_cmt`');
            $dataReader = $cmd->query();
            // calling read() repeatedly until it returns false
            //  while(($row = $dataReader->read())!==false) { ... }
// using foreach to traverse through every row of data
            $systems = array();
            foreach ($dataReader as $row) {
                $systems[$row['object_name']] = $row;
            }
            $dependency = new CFileCacheDependency( /*Yii::getPathOfAlias()*/
                Yii::app()->getModulePath());
            // regenerate $value because it is not found in cache
            // and save it in cache for later use:
            Yii::app()->cache->set($cacheKey, $systems,YII_DEBUG ? 2: 86400, $dependency);

        }
        return $systems;

    }


    /**
     * @return int
     * // it is called on cron every day or similar period
     */
    public function maintenance()
    {
        $time = time() - YS_OLD_VIEWS;
        $systems = self::getAllSystems();
        $iDeletedRecords = 0;
        $db = Yii::app()->db;
        $cmd = $db->createCommand();
        foreach ($systems as $system) {
            if (!$system['enable']) {
                continue;
            }

            $iDeletedRecords += $cmd->setText("DELETE FROM `{$system['table_track']}` WHERE `ts` < {$time}")->execute();
            $cmd->setText("OPTIMIZE TABLE `{$system['table_track']}`")->execute();
        }
        return $iDeletedRecords;
    }


    /**
     * @param array $attributes list of attributes that need to be saved.
    -----------------------------------------------------------------
    array(
    'object_name' => $object_name, //string
    'table_cmt' => $table_cmt, //string
    'table_track' => $table_track, //string
    'per_view' => $per_view, //integer
    'is_ratable' => $is_ratable, //integer
    'is_on' => $is_on, //integer
    'is_mood' => $is_mood, //integer
    'trigger_table' => $trigger_table, //string
    'trigger_field_id' => $trigger_field_id, //string
    'trigger_field_cmts' => $trigger_field_cmts, //string
    'class' => $class, //string
    'extra_config' => $extra_config, //array
    -------------------------------------------
    // see  http://www.yiiframework.com/extension/comments-module
    array(
    //only registered users can post comments
    'registeredOnly' => false,
    'useCaptcha' => false,
    //allow comment tree
    'allowSubcommenting' => true,
    //display comments after moderation
    'premoderate' => false,
    //action for postig comment
    'postCommentAction' => 'comments/comment/postComment',
    //super user condition(display comment list in admin view and automoderate comments)
    'isSuperuser'=>'Yii::app()->user->checkAccess("moderate")',
    //order direction for comments
    'orderComments'=>'DESC',
    )
    -------------------------------------------
    )
    -----------------------------------------------------------------
     * @return mixed
     */
    static public function registerSysObjectCmtConfig($attributes)
    {
        $defaultConfig = array(
            'per_view' => 15,
            'is_ratable' => 0, // 目前不准备支持 评论投票功能 投票现在基本都是 thumbDown 和thumbUp
            'is_on' => 1,
            'is_mood' => 1,

        );
        //  防止传递的 是骆驼命名法作为键值的配置
        //  $attributes = self::transformKeys($attributes);
        $attributes = CMap::mergeArray($defaultConfig, $attributes);

        $attributes['object_name'] = strtolower($attributes['object_name']);
        $sysObjectCmt = new SysObjectCmt();
        $sysObjectCmt->attributes = $attributes;
        $rtn = $sysObjectCmt->save();
        //  WebUtil::printCharsetMeta();
        //  print_r($sysObjectCmt->getErrors());
        return $rtn;
    }

    /**
     * @static
     * @param $sysName ignore the case
     * @return array
     */
    public static function getObjectCmtConfig($sysName)
    {
        $sysName = strtolower($sysName);
        $systems = self::getAllSystems();

        if (!isset($systems[$sysName])) {
            return array();
        }

        $sysObjCmt = SysObjectCmt::model()->populateRecord($systems[$sysName]);
        $config = $sysObjCmt->extra_config;
        return $config;
    }


    /**
     * @static
     * @param string $objectName
     * @return int
     */
    public static function unRegisterObjectCommentConfig($objectName)
    {
        return SysObjectCmt::model()->deleteAllByAttributes(array(
            'object_name' => $objectName,
        ));
    }

    /**
     * convert all keys in a multi-dimenional array to snake_case(underscore)
     * @static
     * @param $array
     */
    protected static function transformKeys(&$array)
    {
        foreach (array_keys($array) as $key) {
            # This is what you actually want to do with your keys:
            #  - remove exclamation marks at the front
            #  - camelCase to snake_case
            $transformedKey = ltrim($key, '!');
            $transformedKey = strtolower($transformedKey[0] . preg_replace('/[A-Z]/', '_$0', substr($transformedKey, 1)));
            # Store with new key
            $array[$transformedKey] = &$array[$key];
            unset($array[$key]);
            # Work recursively
            if (is_array($array[$transformedKey])) {
                self::transformKeys($array[$transformedKey]);
            }
        }
    }
}
