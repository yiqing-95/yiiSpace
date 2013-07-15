<?php
/**
 *
 * User: yiqing
 * Date: 13-7-14
 * Time: 下午10:59
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class JsTemplate extends CComponent
{
    /**
     *
     */
    const ENGINE_DUST_JS = 'dustjs';
    /**
     *
     */
    const ENGINE_ART_TEMPLATE = 'artTemplate';

    /**
     *
     */
    const ENGINE_JUICER = 'juicer';

    /**
     * @see http://handlebarsjs.com/
     */
    const ENGINE_HANDLEBARS = 'Handlebars';

    /**
     *
     */
    const ENGINE_JQUERY_TMPL = 'jquery-tmpl';

    /**
     * @var array
     */
    protected static $packages = array();

    /**
     * @param string $templateName
     */
    public static function registerJs($templateName = 'dustjs')
    {
        $package = self::getPackage($templateName);

        $cs = Yii::app()->getClientScript();
        $cs->packages[$templateName] = $package;
        $cs->registerPackage($templateName);

    }

    /**
     * @param string $templateName
     * @return array
     */
    protected static function getPackage($templateName = 'dustjs')
    {
        self::initPackages();
        return self::$packages[$templateName];
    }

    protected function initPackages()
    {
        if (!empty(self::$packages)) {
            return;
        }

        $vendorsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . '/vendors', false, -1, YII_DEBUG);

        self::$packages = array(
            'dustjs' => array(
                'baseUrl' => $vendorsUrl . '/dustjs',
                'js' => array('dust-full-1.2.3.min.js'),
                'css' => array(),
                'depends' => array(),
            ),
            'artTemplate' => array(
                'baseUrl' => $vendorsUrl . '/artTemplate',
                'js' => array('template.min.js'),
                'css' => array(),
                'depends' => array(),
            ),
            'Handlebars' => array(
                'baseUrl' => $vendorsUrl . '/Handlebars',
                'js' => array('handlebars.js'),
                'css' => array(),
                'depends' => array(),
            ),
            'juicer' => array(
                'baseUrl' => $vendorsUrl . '/juicer',
                'js' => array('juicer-min.js'),
                'css' => array(),
                'depends' => array(),
            ),
            'jquery-tmpl' => array(
                'baseUrl' => $vendorsUrl . '/jquery-tmpl',
                'js' => array('jquery.tmpl.min.js'),
                'css' => array(),
                'depends' => array(),
            ),
        );
    }

}