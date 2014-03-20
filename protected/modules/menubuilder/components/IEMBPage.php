<?php
/**
 * IEMBPage.php
 *
 * Interface for a page model connected to the menubuilder
 * @see EMBPageBehavior
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */

interface IEMBPage
{
    public function getActionView();
}
