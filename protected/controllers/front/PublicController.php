<?php
/**
 * @Desc('this is a test class . you can use test/help to see all available test items')
 */
class PublicController extends YsController
{

    /**
     *
     */
    public function actionTestThumb(){
       // 最后面的 _200x300 将被换掉
      //  echo CHtml::image(bu("/public/thumbs/public/images/banner1.jpg_200x300.jpg"));
        echo Ys::thumbUrl('/public/images/banner1.jpg',200);
    }
    public function actionThumbs()
    {

         // $request = str_replace(DIRECTORY_SEPARATOR . 'thumbs', '', Yii::app()->request->requestUri);
         // $resourcesPath = Yii::getPathOfAlias('webroot') . $request;

        $request = str_replace('/public/thumbs', '', Yii::app()->request->requestUri);

        $resourcesPath = Yii::getPathOfAlias('webroot') . substr($request,strlen(bu())) ;
        $targetPath = Yii::getPathOfAlias('webroot') . substr(Yii::app()->request->requestUri,strlen(bu()));

        //die($targetPath);

        if (preg_match('/_(\d+)x(\d+).*\.(jpg|jpeg|png|gif)/i', $resourcesPath, $matches)) {

            if (!isset($matches[0]) || !isset($matches[1]) || !isset($matches[2]) || !isset($matches[3]))
                throw new CHttpException(400, 'Non valid params');

            if (!$matches[1] || !$matches[2]) {
                throw new CHttpException(400, 'Invalid dimensions');
            }

            $originalFile = str_replace($matches[0], '', $resourcesPath);


            if (!file_exists($originalFile))
                throw new CHttpException(404, 'File not found: '.$originalFile);

            $dirname = dirname($targetPath);

            // die($matches[0]);

            if (!is_dir($dirname))
                mkdir($dirname, 0775, true);

            /*
            $image = Yii::app()->image->load($originalFile);
            $image->resize($matches[1], $matches[2]);

            if ($image->save($targetPath)) {
                if (Yii::app()->request->urlReferrer != Yii::app()->request->requestUri)
                    $this->refresh();
            }*/
            $phpThumb = AppComponent::phpThumb()->create($originalFile);
            //$phpThumb->resize(550,800);
            $phpThumb->adaptiveResize($matches[1], $matches[2]);
            $phpThumb->save($targetPath);
            if(is_file($targetPath)){
                if (Yii::app()->request->urlReferrer != Yii::app()->request->requestUri)
                    $this->refresh();
            }

            throw new CHttpException(500, 'Server error');
        } else {
            throw new CHttpException(400, 'Wrong params');
        }
    }

}