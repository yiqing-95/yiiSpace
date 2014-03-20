<?php
/**
 * Sluggable Behavior for Yii Framework.
 *
 * @author    Florian Fackler <florian.fackler@mintao.com>
 * @link      http://mintao.com/
 * @copyright Copyright &copy; 2009 Mintao GmbH & Co. KG
 * @license   WTFPL
 * @version   $Id: SluggableBehavior.php 530 2011-04-30 23:31:12Z florian.fackler $
 * @package   components
 */
include_once __DIR__ . '/Doctrine_Inflector.php';

/**
 * SluggableBehavior
 *
 * @uses      CActiveRecordBehavior
 * @package
 * @version   $id$
 * @copyright 2011 mintao GmbH & Co. KG
 * @author    Florian Fackler <florian.fackler@mintao.com>
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class SluggableBehavior extends CActiveRecordBehavior
{
    /**
     * @var array Column name(s) to build a slug
     */
    private $columns = array();

    /**
     * Wether the slug should be unique or not.
     * If set to true, a number is added
     *
     * @var bool
     */
    private $unique = true;

    /**
     * @param boolean $unique
     */
    public function setUnique($unique)
    {
        $this->unique = !!$unique;
    }

    /**
     * @return boolean
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * Update the slug every time the row is updated?
     *
     * @var bool $update
     */
    private $update = true;

    /**
     * @param boolean $update
     */
    public function setUpdate($update)
    {
        $this->update = !!$update;
    }

    /**
     * @return boolean
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Name of table column to store the slug in
     *
     * @var string $slugColumn
     */
    private $slugColumn = 'slug';

    /**
     * @param string $slugColumn
     */
    public function setSlugColumn($slugColumn)
    {
        $this->slugColumn = (string)$slugColumn;
    }

    /**
     * @return string
     */
    public function getSlugColumn()
    {
        return $this->slugColumn;
    }

    /**
     * If Doctrine Inflector is turned on, all special chars are
     * replaced by standard a-z 0-9 chars.
     * If you turn this iss only whitespaces will be
     * replaced by dashes.
     *
     * @var bool
     */
    private $useInflector = true;

    /**
     * @param boolean $useInflector
     */
    public function setUseInflector($useInflector)
    {
        $this->useInflector = !!$useInflector;
    }

    /**
     * @return boolean
     */
    public function getUseInflector()
    {
        return $this->useInflector;
    }

    /**
     * Transform the slug to lower case
     *
     * @var boolean
     * @access public
     */
    private $toLower = false;

    /**
     * @param boolean $toLower
     */
    public function setToLower($toLower)
    {
        $this->toLower = !!$toLower;
    }

    /**
     * @return boolean
     */
    public function getToLower()
    {
        return $this->toLower;
    }

    /**
     * Default columns to build slug if none given
     *
     * @var array Columns
     */
    protected $_defaultColumnsToCheck = array('name', 'title');

    /**
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * beforeSave
     *
     * @param CModelEvent $event
     *
     * @throws CException
     * @access public
     */
    public function beforeSave($event)
    {
        // Slug already created and no updated needed
        if (true !== $this->update && !empty($this->getOwner()->{$this->slugColumn})) {
            Yii::trace(
                'Slug found - no update needed.',
                __CLASS__ . '::' . __FUNCTION__
            );

            return parent::beforeSave($event);
        }

        $availableColumns = array_keys(
            $this->getOwner()->tableSchema->columns
        );

        // Try to guess the right columns
        if (0 === count($this->columns)) {
            $this->columns = array_intersect(
                $this->_defaultColumnsToCheck,
                $availableColumns
            );
        } else {
            // Unknown columns on board?
            foreach ($this->columns as $col) {
                if (!in_array($col, $availableColumns)) {
                    if (false !== strpos($col, '.')) {
                        Yii::trace(
                            'Dependencies to related models found',
                            __CLASS__
                        );
                        list($model, $attribute) = explode('.', $col);
                        $externalColumns = array_keys(
                            $this->getOwner()->$model->tableSchema->columns
                        );
                        if (!in_array($attribute, $externalColumns)) {
                            throw new CException(
                                "Model $model does not haz $attribute"
                            );
                        }
                    } else {
                        throw new CException(
                            'Unable to build slug, column ' . $col . ' not found.'
                        );
                    }
                }
            }
        }

        // No columns to build a slug?
        if (0 === count($this->columns)) {
            throw new CException(
                'You must define "columns" to your sluggable behavior.'
            );
        }

        // Fetch values
        $values = array();
        foreach ($this->columns as $col) {
            if (false === strpos($col, '.')) {
                $values[] = $this->getOwner()->$col;
            } else {
                list($model, $attribute) = explode('.', $col);
                $values[] = $this->getOwner()->$model->$attribute;
            }
        }

        // First version of slug
        if (true === $this->useInflector) {
            $slug = $checkslug = Doctrine_Inflector::urlize(
                implode('-', $values)
            );
        } else {
            $slug = $checkslug = $this->simpleSlug(
                implode('-', $values)
            );
        }

        // Check if slug has to be unique
        if (false === $this->unique
            ||
            (!$this->getOwner()->getIsNewRecord()
                && $slug === $this->getOwner()->{$this->slugColumn})
        ) {
            Yii::trace('Non unique slug or slug already set', __CLASS__);
            $this->getOwner()->{$this->slugColumn} = $slug;
        } else {
            $counter = 0;
            while ($this->getOwner()->resetScope()
                ->findByAttributes(array($this->slugColumn => $checkslug))
            ) {
                Yii::trace("$checkslug found, iterating", __CLASS__);
                $checkslug = sprintf('%s-%d', $slug, ++$counter);
            }
            $this->getOwner()->{$this->slugColumn} = $counter > 0 ? $checkslug : $slug;
        }

        return parent::beforeSave($event);
    }

    /**
     * Create a simple slug by just replacing white spaces
     *
     * @param string $str
     *
     * @access protected
     * @return string
     */
    private function simpleSlug($str)
    {
        $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $str);
        if (true === $this->toLower) {
            $slug = mb_strtolower($slug, \Yii::app()->charset);
        }
        $slug = trim($slug, '-');

        return $slug;
    }
}

