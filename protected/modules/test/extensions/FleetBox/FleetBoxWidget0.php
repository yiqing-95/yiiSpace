<?php
/**
 * FleetBoxWidget
 * @author Dmitriev Sergey
 * 2012
 * inspired by Fleetio.com
 * Usage
 * <?php
 *       $this->widget("ext.widgets.FleetBox.FleetBoxWidget", [
 *           'header' => [
 *               'title' => 'Header',
 *               'addon' => '',         //additional string with data near header
 *               'actions' => [
 *                   CHtml::link('add', '#', ['class' => 'btn btn-mini']),
 *                   CHtml::link('delete', '#'),
 *                   ]
 *               ],
 *           'size' => 'small',         //'','large','small'
 *           'body' => 'FleetBox size:small',              
 *           ]
 *       );
 *       ?>
 */
class FleetBoxWidget extends CWidget {

    public $header;
    public $size;
    public $body;

    public function init() {
        $cs = Yii::app()->getClientScript();
        $assetsUrl = Yii::app()->assetManager->publish(__DIR__ . '/assets');
        $cs->registerCssFile($assetsUrl . '/fleetbox.css');
    }

    public function run() {

        echo $this->renderBox();
    }

    protected function renderBox() {

        $header = $this->renderHeader();
        $body = CHtml::tag('div', ['class' => 'section-body'], $this->body);

        $content = $header . $body;
        $class = ($this->size == 'small') ? 'section-small' : '';
        $class = ($this->size == 'large') ? 'section-large' : $class;

        return CHtml::tag('div', array('class' => 'section ' . $class), $content);
    }

    protected function renderHeader(){
        $header = $this->header;
        $h = (isset($this->size) && ($this->size == 'small')) ? 'h5' : 'h3';

        $addon = (isset($header['addon']) && !empty($header['addon'])) ? 
            ' '.CHtml::tag('small', [], $header['addon']) : '';
        
        $html = CHtml::tag($h, [], $header['title'] . $addon);
        
        $htmlOptions = (isset($header['htmlOptions']))?$header['htmlOptions']:[];
        $htmlOptions['class'] = 'section-header'.
                (isset($htmlOptions['class'])?' '.$htmlOptions['class']:'');
        
        return CHtml::tag('div', $htmlOptions, $html.$this->actionsList());
    }
    
    protected function actionsList() {
        $header = $this->header;
        $li = '';
        if ($header['actions']) {
            foreach ($header['actions'] as $action) {
                $li .= CHtml::tag('li', [], $action);
            }
            return CHtml::tag('ul', [], $li);
        }
    }

}

?>
