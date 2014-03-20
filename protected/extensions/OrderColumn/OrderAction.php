<?php

/**
 * Description of OrderAction
 *
 * @author Nr Aziz
 */
class OrderAction extends CAction {

    public $modelClass;
    public $pkName;

    public function run($pk, $name, $value, $move) {
        $model = CActiveRecord::model($this->modelClass)->findByPk($pk);
        $table = $model->tableName();
        if ($move === 'up') {
            $op = '<=';
            $inOrder = 'DESC';
        } else if ($move === 'down') {
            $op = '>=';
            $inOrder = 'ASC';
        }

        $sql = "SELECT {$table}.{$name} FROM $table WHERE $this->pkName $op $pk ORDER BY $this->pkName $inOrder LIMIT 1";
        $order = Yii::app()->db->createCommand($sql)->queryScalar();


        $highestOrder = Yii::app()
                ->db
                ->createCommand("SELECT {$table}.{$name} FROM {$table} ORDER BY {$table}.{$name} DESC LIMIT 1")
                ->queryScalar();


        if ($move === 'up' && $model->{$name} != 0)
            $order -= 1;
        else if ($move === 'down' && $order != $highestOrder+1)
            $order += 1;

        $model->{$name} = $order;
        $model->save(false);
    }

}

?>
