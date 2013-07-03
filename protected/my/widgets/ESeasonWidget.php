<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 11-10-13
 * Time: 下午9:05
 * @see http://css-tricks.com/snippets/php/change-graphics-based-on-season/
 * ------------------------------------------------------------------------------------------
 *
 * ------------------------------------------------------------------------------------------
 */

class ESeasonWidget extends CWidget
{

    /**
     * @var array
     */
    public $defaultSeasonCssClass = array(
        'spring' => 'spring',
        'summer' => 'summer',
        'autumn' => 'autumn',
        'winter' => 'winter',
    );

    /**
     * @var array
     * use this to overwrite the default one
     */
    public $seasonCss = array(

    );

    /**
     * @var string
     */
    public $selector;

    /**
     * @throws CException
     * @return void
     */
    public function init()
    {
        if (empty($this->selector)) {
            throw new CException("you must give a selector for using this widget! ");
        }

        $this->registerClientScripts();
    }


    /**
     * @return KScrollToWidget
     * if  there is  some assets folder to  publish  your can implements this method
     * this is just for future using
     */
    public function publishAssets()
    {
        return $this;
    }


    public function registerClientScripts()
    {
        // register the jquery and   plugin code
        Yii::app()->getClientScript()->registerCoreScript('jquery')
            ->registerScript(__CLASS__ . '_plugin', $this->jsCode(), CClientScript::POS_END);

        $seasonCss = CMap::mergeArray($this->defaultSeasonCssClass,$this->seasonCss);
        $seasonCss = CJavaScript::encode($seasonCss);

        $addClass2target = <<<JS
        var seasonCss = {$seasonCss};
        var currentSeason = getCurrentSeason();
   $("{$this->selector}").addClass(seasonCss[currentSeason]);
JS;
        cs()->registerScript(__CLASS__ . '#' . $this->getId(), $addClass2target, CClientScript::POS_READY);
        return $this;
    }

    public function jsCode()
    {
        $js = <<<JS_FUNC
function dayofyear(d) {   // d is a Date object
var yn = d.getFullYear(),
    mn = d.getMonth(),
    dn = d.getDate(),
    d1 = new Date(yn,0,1,12,0,0), // noon on Jan. 1
    d2 = new Date(yn,mn,dn,12,0,0), // noon on input date
    ddiff = Math.round((d2-d1)/864e5);
    return ddiff+1;
}

function getCurrentSeason(){
var curdate=new Date(); // gets today's date
var cdnum=dayofyear(curdate);
var season ;
if ( cdnum < 79) { season = "winter"; }
        else if ( cdnum < 171) { season = "spring"; }
        else if ( cdnum < 265) { season = "summer"; }
        else if ( cdnum < 354) { season = "autumn"; }
        else season = "winter";
       return season;
}
JS_FUNC;
        return $js;
    }


    /**
     * @static
     * @return string
     */
    static public function  getCurrentSeason()
    {
        // What is today's date - number
        $day = date("z");
        //  Days of spring
        $spring_starts = date("z", strtotime("March 21"));
        $spring_ends = date("z", strtotime("June 20"));
        //  Days of summer
        $summer_starts = date("z", strtotime("June 21"));
        $summer_ends = date("z", strtotime("September 22"));
        //  Days of autumn
        $autumn_starts = date("z", strtotime("September 23"));
        $autumn_ends = date("z", strtotime("December 20"));
        //  If $day is between the days of spring, summer, autumn, and winter
        if ($day >= $spring_starts && $day <= $spring_ends) :
            $season = "spring"; elseif ($day >= $summer_starts && $day <= $summer_ends) :
            $season = "summer";
        elseif ($day >= $autumn_starts && $day <= $autumn_ends) :
            $season = "autumn";
        else :
            $season = "winter";
        endif;
        return $season;
    }


}
