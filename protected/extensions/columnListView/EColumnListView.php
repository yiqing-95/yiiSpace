<?php

/**
 * EColumnListView class file.
 *
 * @author Tasos Bekos <tbekos@gmail.com>
 * @copyright Copyright &copy; 2012 Tasos Bekos
 */
/**
 * EColumnListView represents a list view in multiple columns.
 *
 * @author Tasos Bekos <tbekos@gmail.com>
 */
Yii::import('zii.widgets.CListView');

class EColumnListView extends CListView {

    /**
     *
     * @var mixed integer the number of columns
     */
    public $columns = 2;

    /**
     * Renders the item view.
     * This is the main entry of the whole view rendering.
     *
     * This is override function that supports multiple columns
     */
    public function renderItems() {
        $numColumns = (int) $this->columns; // Number of columns

        if ($numColumns < 2) {
            parent::renderItems();
            return;
        }

        echo CHtml::openTag($this->itemsTagName, array('class' => $this->itemsCssClass)) . "\n";
        $data = $this->dataProvider->getData();

        if (($n = count($data)) > 0) {

            // Compute column width
            $width = 100 / $numColumns;

            // Initialize table
            echo CHtml::openTag('table') . CHtml::openTag('tr');

            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';
            //$j = 0;
            foreach ($data as $i => $item) {

                // Open cell
                echo CHtml::openTag('td', array('style' => 'width:' . $width . '%; vertical-align:top;'));

                $data = $this->viewData;
                $data['index'] = $i;
                $data['data'] = $item;
                $data['widget'] = $this;
                $owner->$render($this->itemView, $data);

                // Close cell
                echo CHtml::closeTag('td');

                // Change row
                if (($i + 1) % $numColumns == 0) {
                    echo CHtml::closeTag('tr') . CHtml::openTag('tr');
                }
            }

            // Close table
            echo CHtml::closeTag('tr') . CHtml::closeTag('table');
        } else {
            $this->renderEmptyText();
        }
        echo CHtml::closeTag($this->itemsTagName);
    }

}

?>