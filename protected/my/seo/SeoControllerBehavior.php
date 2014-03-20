<?php



class SeoControllerBehavior extends CBehavior
{
    /**
     * @param Seo $seoModel
     */
    public function registerSeo($seoModel )
    {
        //if seo data presents for model, then register it for page
        if(!empty($seoModel) && !$seoModel->getIsNewRecord())
        {
            if(!empty($seoModel->title)){
                $this->owner->setPageTitle(CHtml::encode($seoModel->title));
            }
            Yii::app()->clientScript
                    ->registerMetaTag(CHtml::encode($seoModel->keywords), 'keywords', 'keywords')
                    ->registerMetaTag(CHtml::encode($seoModel->description), 'description', 'description');
        }    
    }
}

?>
