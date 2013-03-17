<?php
/**
 * Logs to every row who created and who updated it.
 * This may interests you when working in a group of
 * more people sharing same privileges.
 *
 * @copyright mintao GmbH & Co. KG
 * @author Florian Fackler <florian.fackler@mintao.com>
 * @license WTFPL Version 2 <http://sam.zoy.org/wtfpl/>
 * @package Yii framework
 * @subpackage db-behavior
 * @version 0.1 Beta
 */

class BlameableBehavior extends CActiveRecordBehavior
{
    /**
     * Name of the column in the table where to write the creater user name
     *
     * @param string $createdByColumn
     */
    public $createdByColumn = 'created_by';

    /**
     * Name of the column in the table where to write the updater user name
     *
     * @param string $updatedByColumn
     */
    public $updatedByColumn = 'updated_by';

    /**
     * If set to true the updated_by column is also set with every new record.
     * Set to false to fill this column only on updates of an existing record.
     *
     * @var string
     */
    public $setUpdateOnCreate = true;

    /**
     * Yii model behavior executed before a record set is saved
     *
     * @param CModelEvent $event
     * @return void
     * @author Florian Fackler
     */
    public function beforeSave(CModelEvent $event)
    {
        $username = 'unknown';

        // If the user is logged in, use his username
        if (isset(Yii::app()->user)) {
            $username = Yii::app()->user->id;
        }

        $availableColumns = array_keys(
            $this->getOwner()->tableSchema->columns
        );

        if ($this->getOwner()->isNewRecord 
            && empty($this->getOwner()->{$this->createdByColumn})
        ) {
            if (in_array($this->createdByColumn, $availableColumns)) {
                $this->getOwner()->{$this->createdByColumn} = $username;
            }
        }

        if (true === $this->setUpdateOnCreate 
            && empty($this->getOwner()->{$this->updatedByColumn})
        ) {
            if (in_array($this->updatedByColumn, $availableColumns)) {
                $this->getOwner()->{$this->updatedByColumn} = $username;
            }
        }
        return parent::beforeSave($event);
    }
}
