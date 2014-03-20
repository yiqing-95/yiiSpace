<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-17
 * Time: 上午3:33
 * To change this template use File | Settings | File Templates.
 */

class StepTestForm extends CFormModel
{
    public function getForm() {
    return new CForm(array(
        'showErrorSummary'=>true,
        'buttons'=>array(
            'submit'=>array(
                'type'=>'submit',
                'label'=>'Install/Upgrade',
                'id' => 'installButton',
            ),
        )
    ), $this);
}
}
