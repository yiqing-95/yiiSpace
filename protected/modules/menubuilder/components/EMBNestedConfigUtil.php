<?php
/**
 * EMBNestedConfigUtil.php
 *
 * Class with static methods to handle the nested config
 * inserting, removing, moving ids in the nestedconfig json datastructur:
 * [{"id":13},{"id":14},{"id":15,"children":[{"id":16},{"id":17},{"id":18}]} .... ]
 *
 * Usable for direct manipulation of the nestedconfig without the UI
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

class EMBNestedConfigUtil
{

    /**
     * Check if the nestedconfig is empty
     *
     * @param $nestedConfig
     * @return bool
     */
    public static function isEmptyNestedConfig($nestedConfig)
    {
        //change [] to ''
        if(!empty($nestedConfig))
        {
            $array = json_decode($nestedConfig);
            return empty($array);
        }

        return true;
    }

    /**
     * Check if the nestedconfig includes a id
     *
     * @param $nestedConfig
     * @param $id
     * @return bool
     */
    public static function includesId($nestedConfig,$id)
    {
       return !empty($nestedConfig) ? strpos($nestedConfig,'"id":"'.$id.'"') !== false : false;
    }


    /**
     * Extract all ids from the nestedConfig
     *
     * @param $nestedConfig
     * @return array
     */
    public static function extractExistingIds($nestedConfig)
    {
        $existing = array();
        if(empty($nestedConfig))
            return $existing;

        $array = CJSON::decode($nestedConfig);
        self::_extractExistingIds($array,$existing);

        return $existing;
    }

    /**
     * Recursive method for extracting the ids
     *
     * @param $array
     * @param array $existing
     */
    public static function _extractExistingIds($array,&$existing=array())
    {
        foreach ($array as $key => $value)
            if (isset($value['id']))
            {
                if(!in_array($value['id'],$existing))
                    $existing[]=$value['id'];

                if (!empty($value['children']))
                {
                    $tmp = $value['children'];
                    self::_extractExistingIds($tmp,$existing);
                }
            }
    }

    /**
     * Remove a id from the nestedConfig
     *
     * @param $nestedConfig
     * @param $id
     * @return string
     */
    public static function removeId($nestedConfig,$id)
    {
        if (self::includesId($nestedConfig,$id))
        {
            $array = CJSON::decode($nestedConfig);
            $removed = false;
            self::_remove($array, $removed, $id);
            if ($removed)
            {
                return json_encode($array); //not korrekt with CJSON::encode
            }
        }

        return $nestedConfig;
    }

    /**
     * Recursive method for removing the id
     *
     * @param $array
     * @param $removed
     * @param $id
     */
    protected static function _remove(&$array, &$removed, $id)
    {
        if ($removed)
            return;

        foreach ($array as $key => $value)
            if (isset($value['id']))
            {
                if ($value['id'] == $id)
                {
                    unset($array[$key]);
                    $removed = true;
                    return;
                }
                if (!empty($value['children']))
                {
                    $tmp = $value['children'];
                    self::_remove($tmp, $removed, $id);
                    if ($removed)
                    {
                        $array[$key] = empty($tmp) ? array('id' => $value['id']) : array('id' => $value['id'], 'children' => array_values($tmp));
                        return;
                    }
                }
            }
    }

    /**
     * Move a id before another id
     *
     * @param $nestedConfig
     * @param $beforeId
     * @param $id
     * @return mixed|string
     */
    public static function moveBefore($nestedConfig,$beforeId, $id)
    {
        if(!self::includesId($nestedConfig,$beforeId))
            return $nestedConfig;

        if(self::includesId($nestedConfig,$id))
            $nestedConfig = self::removeId($nestedConfig,$id);

        $searchStr = '{"id":' . $beforeId . '}';
        if (strpos($nestedConfig, $searchStr) !== false)
        {
            $replaceIdStr = '{"id":' . $id . '},'.$searchStr;
            return str_replace($searchStr, $replaceIdStr, $nestedConfig);
        }

        $searchStr = '{"id":' . $beforeId . ',"children"';
        if (strpos($nestedConfig, $searchStr) !== false)
        {
            $replaceIdStr = '{"id":' . $beforeId.'},{"id":' . $id. ',"children"';
            return str_replace($searchStr, $replaceIdStr, $nestedConfig);
        }

        return $nestedConfig;
    }

    /**
     * Move a id after another id
     *
     * @param $nestedConfig
     * @param $afterId
     * @param $id
     * @return mixed|string
     */
    public static function moveAfter($nestedConfig,$afterId, $id)
    {
        if(!self::includesId($nestedConfig,$afterId))
            return $nestedConfig;

        if(self::includesId($nestedConfig,$id))
            $nestedConfig = self::removeId($nestedConfig,$id);

        $searchStr = '{"id":' . $afterId . '}';
        if (strpos($nestedConfig, $searchStr) !== false)
        {
            $replaceIdStr = '{"id":' . $afterId.'},{"id":' . $id . '}';
            return str_replace($searchStr, $replaceIdStr, $nestedConfig);
        }

        $searchStr = '{"id":' . $afterId . ',"children"';
        if (strpos($nestedConfig, $searchStr) !== false)
        {
            $replaceIdStr = '{"id":' . $afterId.'},{"id":' . $id. ',"children"';
            return str_replace($searchStr, $replaceIdStr, $nestedConfig);
        }

        return $nestedConfig;
    }

    /**
     * Move a id to the first position
     *
     * @param $nestedConfig
     * @param $id
     * @return string
     */
    public static function moveToFirst($nestedConfig,$id)
    {
        if(self::includesId($nestedConfig,$id))
            $nestedConfig = self::removeId($nestedConfig,$id);

        if (!empty($nestedConfig))
            $nestedConfig = '[{"id":' . $id . '},' . substr($nestedConfig, 1);
        else
           $nestedConfig = '[{"id":' . $id . '}]';

        return $nestedConfig;
    }

    /**
     * Move a id to the last position
     *
     * @param $nestedConfig
     * @param $id
     * @param bool $checkFindModel
     * @return string
     */
    public static function moveToLast($nestedConfig,$id, $checkFindModel = true)
    {
        if(self::includesId($nestedConfig,$id))
            $nestedConfig = self::removeId($nestedConfig,$id);

        if (!empty($nestedConfig))
            $nestedConfig = substr($nestedConfig, 0, -1) . ',{"id":' . $id . '}]';
        else
            $nestedConfig = '[{"id":' . $id . '}]';

        return $nestedConfig;
    }

}