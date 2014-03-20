<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-8
 * Time: 上午8:06
 */

namespace upload;

/**
 * 实现参考YsUploadStorage的同名方法们！
 *
 * Interface IUploadStorage
 * @package upload
 */
interface IUploadStorage {

    /**
     * 上传本地文件到文件存储
     *
     * @param $localPath
     * @return string fileUri  返回文件存储的URI
     *
     * 文件URI可以是特定的组合 不一定需要是文件的存储路径
     * groupId|nodeId|cateId|xxxxxxx.jpg
     */
    public function upload($localPath);


    /**
     * 根据文件的URI删除掉上传的文件
     *
     * @param $fileUri
     * @return mixed
     */
    public function deleteFile($fileUri);

    /**
     * 根据文件的URI 获取其可被web访问的URL
     *
     * @param $fileUri
     * @return mixed
     */
    public function getUrl($fileUri);


    /**
     * return the thumbnailUrl calculated by the fileURI
     *
     * @param string $fileUri
     * @param $height
     * @param int $width
     * @param string $suffix
     * @return string
     */
    public function getThumbUrl($fileUri='',$height,$width=0,$suffix='');
} 