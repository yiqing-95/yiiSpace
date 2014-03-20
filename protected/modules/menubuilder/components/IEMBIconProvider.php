<?php
/**
 * IEMBIconProvider.php
 *
 * Interface for a iconProvider
 * @see EMBBootstraIconProvider
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

interface IEMBIconProvider
{
    public function registerClientScript();
    public function dropDownList($model,$attribute,$htmlOptions=array());
    public static function getIconLabel($icon,$label);
}
