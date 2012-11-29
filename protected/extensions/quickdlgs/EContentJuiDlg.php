<?php
/**
 * EFrameJuiDlg renders a button/link or icon to display content in a CJuiDialog
 *
 * Usage: see EQuickDlg::contentButton, EQuickDlg::contentLink, EQuickDlg::contentIcon
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2012 myticket it-solutions gmbh
 * @license New BSD License
 * @version 1.2
 * @package ext.quickdlgs
 * @since 1.0
 */

class EContentJuiDlg extends EBaseJuiDlg
{
    public $content;

    public function renderDialogContent()
    {
        echo $this->content;
    }

}
