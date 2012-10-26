<?php

/**
 * GiixModelCode class file.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @link http://giix.org/
 * @copyright Copyright &copy; 2010-2011 Rodrigo Coelho
 * @license http://giix.org/license/ New BSD License
 */
Yii::import('system.gii.generators.model.ModelCode');
Yii::import('ext.giix-core.helpers.*');

/**
 * GiixModelCode is the model for giix model generator.
 *
 * @author Rodrigo Coelho <rodrigo@giix.org>
 * @since 1.0
 */
class GiixModelCode extends ModelCode
{

    /**
     * @var string The (base) model base class name.
     */
    public $baseClass = 'GxActiveRecord';
    /**
     * @var string The path of the base model.
     */
    public $baseModelPath;
    /**
     * @var string The base model class name.
     */
    public $baseModelClass;

    /**
     * Prepares the code files to be generated.
     * #MethodTracker
     * This method is based on {@link ModelCode::prepare}, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Generates the base model.</li>
     * <li>Provides the representing column for the table.</li>
     * <li>Provides the pivot class names for MANY_MANY relations.</li>
     * </ul>
     */
    public function prepare()
    {
        if (($pos = strrpos($this->tableName, '.')) !== false) {
            $schema = substr($this->tableName, 0, $pos);
            $tableName = substr($this->tableName, $pos + 1);
        } else {
            $schema = '';
            $tableName = $this->tableName;
        }
        if ($tableName[strlen($tableName) - 1] === '*') {
            $tables = Yii::app()->db->schema->getTables($schema);
            if ($this->tablePrefix != '') {
                foreach ($tables as $i => $table) {
                    if (strpos($table->name, $this->tablePrefix) !== 0)
                        unset($tables[$i]);
                }
            }
        }
        else
            $tables = array($this->getTableSchema($this->tableName));

        $this->files = array();
        $templatePath = $this->templatePath;

        $this->relations = $this->generateRelations();

        foreach ($tables as $table) {
            $tableName = $this->removePrefix($table->name);
            $className = $this->generateClassName($table->name);

            // Generate the pivot model data.
            $pivotModels = array();
            if (isset($this->relations[$className])) {
                foreach ($this->relations[$className] as $relationName => $relationData) {
                    if (preg_match('/^array\(self::MANY_MANY,.*?,\s*\'(.+?)\(/', $relationData, $matches)) {
                        // Clean the table name if needed.
                        $pivotTableName = str_replace(array('{', '}'), '', $matches[1]);
                        $pivotModels[$relationName] = $this->generateClassName($pivotTableName);
                    }
                }
            }

            $params = array(
                'tableName' => $schema === '' ? $tableName : $schema . '.' . $tableName,
                'modelClass' => $className,
                'columns' => $table->columns,
                'labels' => $this->generateLabels($table),
                'rules' => $this->generateRules($table),
                'relations' => isset($this->relations[$className]) ? $this->relations[$className] : array(),
                'representingColumn' => $this->getRepresentingColumn($table), // The representing column for the table.
                'pivotModels' => $pivotModels, // The pivot models.
            );
            // Setup base model information.
            $this->baseModelPath = $this->modelPath . '._base';
            $this->baseModelClass = 'Base' . $className;
            // Generate the model.
            $this->files[] = new CCodeFile(
                Yii::getPathOfAlias($this->modelPath . '.' . $className) . '.php',
                $this->render($templatePath . DIRECTORY_SEPARATOR . 'model.php', $params)
            );
            // Generate the base model.
            $this->files[] = new CCodeFile(
                Yii::getPathOfAlias($this->baseModelPath . '.' . $this->baseModelClass) . '.php',
                $this->render($templatePath . DIRECTORY_SEPARATOR . '_base' . DIRECTORY_SEPARATOR . 'basemodel.php', $params)
            );
        }
    }

    /**
     * Lists the template files.
     * #MethodTracker
     * This method is based on {@link ModelCode::requiredTemplates}, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Includes the base model.</li>
     * </ul>
     * @return array A list of required template filenames.
     */
    public function requiredTemplates()
    {
        return array(
            'model.php',
            '_base' . DIRECTORY_SEPARATOR . 'basemodel.php',
        );
    }

    /**
     * Generates the rules for table fields.
     * #MethodTracker
     * This method overrides {@link ModelCode::generateRules}, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Adds the rule to fill empty attributes with null.</li>
     * </ul>
     * @param CDbTableSchema $table The table definition.
     * @return array The rules for the table.
     */
    public function generateRules($table)
    {
        $rules = array();
        $null = array();
        foreach ($table->columns as $column) {
            if ($column->autoIncrement)
                continue;
            if (!(!$column->allowNull && $column->defaultValue === null))
                $null[] = $column->name;
        }
        if ($null !== array())
            $rules[] = "array('" . implode(', ', $null) . "', 'default', 'setOnEmpty' => true, 'value' => null)";

        return array_merge(parent::generateRules($table), $rules);
    }

    /**
     * Selects the representing column of the table.
     * This field will be the responsible for the string representation of
     * the model instance.
     * @param CDbTableSchema $table a table definition.
     * @return string|array the name of the column as a string or the names of the columns as an array.
     */
    protected function getRepresentingColumn($table)
    {
        $columns = $table->columns;
        // If this is not a MANY_MANY pivot table
        if (!$this->isRelationTable($table)) {
            // First we look for a string, not null, not pk, not fk column, not original number on db.
            foreach ($columns as $name => $column) {
                if ($column->type === 'string' && !$column->allowNull && !$column->isPrimaryKey && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
                    return $name;
            }
            // Then a string, not null, not fk column, not original number on db.
            foreach ($columns as $name => $column) {
                if ($column->type === 'string' && !$column->allowNull && !$column->isForeignKey && stripos($column->dbType, 'int') === false)
                    return $name;
            }
            // Then the first string column, not original number on db.
            foreach ($columns as $name => $column) {
                if ($column->type === 'string' && stripos($column->dbType, 'int') === false)
                    return $name;
            }
        } // If the appropriate column was not found or if this is a MANY_MANY pivot table.
        // Then the pk column(s).
        $pk = $table->primaryKey;
        if ($pk !== null) {
            if (is_array($pk))
                return $pk;
            else
                return (string)$pk;
        }
        // Then the first column.
        return reset($columns)->name;
    }

    /**
     * Returns the message to be displayed when the newly generated code is saved successfully.
     * #MethodTracker
     * This method overrides {@link CCodeModel::successMessage}, from version 1.1.7 (r3135). Changes:
     * <ul>
     * <li>Custom giix success message.</li>
     * </ul>
     * @return string The message to be displayed when the newly generated code is saved successfully.
     */
    public function successMessage()
    {
        return <<<EOM
<p><strong>Sweet!</strong></p>
<ul style="list-style-type: none; padding-left: 0;">
	<li><img src="http://giix.org/icons/love.png"> Show how you love giix on <a href="http://www.yiiframework.com/forum/index.php?/topic/13154-giix-%E2%80%94-gii-extended/">the forum</a> and on its <a href="http://www.yiiframework.com/extension/giix">extension page</a></li>
	<li><img src="http://giix.org/icons/vote.png"> Upvote <a href="http://www.yiiframework.com/extension/giix">giix</a></li>
	<li><img src="http://giix.org/icons/powered.png"> Show everybody that you are using giix in <a href="http://www.yiiframework.com/forum/index.php?/topic/19226-powered-by-giix/">Powered by giix</a></li>
	<li><img src="http://giix.org/icons/donate.png"> <a href="http://giix.org/">Donate</a></li>
</ul>
<p style="margin: 2px 0; position: relative; text-align: right; top: -15px; color: #668866;">icons by <a href="http://www.famfamfam.com/lab/icons/silk/" style="color: #668866;">famfamfam.com</a></p>
EOM;
    }

}