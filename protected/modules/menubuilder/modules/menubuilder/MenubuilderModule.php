<?php
/**
 * MenubuilderModule
 *
 * Copy the 'menubuilder' module to protected/modules
 * This is your working copy where you can override and extend the menubuilder
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright Copyright &copy; myticket it-solutions gmbh
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package menubuilder
 * @category User Interface
 * @version 1.0
 */
Yii::import('ext.menubuilder.EMBMenubuilderModule');

class MenubuilderModule extends EMBMenubuilderModule
{

    /**
     * Import the components
     */
    public function init()
    {
        parent::init();

        $this->setImport(array(
            $this->name.'.models.*',
            $this->name.'.components.*',
            $this->name.'.widgets.*',
        ));
    }

}