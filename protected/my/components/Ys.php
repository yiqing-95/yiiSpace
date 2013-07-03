<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-11-13
 * Time: 上午9:53
 * To change this template use File | Settings | File Templates.
 */
class Ys
{

    /**
     * @param string $sourceImgUrl
     * @param int $width
     * @param int $height
     * @param string $suffix
     * @return string
     */
 static    public function thumbUrl($sourceImgUrl,$height,$width=0,$suffix=''){
        $thumbUrlHandlerRoute = 'public/thumbs';
        if($width == 0){
             $width = $height;
        }
        if($suffix == ''){
          $suffix =  pathinfo($sourceImgUrl, PATHINFO_EXTENSION);
        }
        //$thumbUrl = $thumbUrlHandlerRoute .'/'.ltrim($sourceImgUrl)."_{$height}x{$width}.{$suffix}";
        return bu($thumbUrlHandlerRoute .'/'.ltrim($sourceImgUrl)."_{$height}x{$width}.{$suffix}");
    }
}
