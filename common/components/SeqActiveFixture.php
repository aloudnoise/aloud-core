<?php
namespace common\components;

use yii\test\ActiveFixture;

class SeqActiveFixture extends ActiveFixture
{
    protected $sequenceName = 'id';

    /**
     * После загрузки меняем последовательность на максимальное число
     */
    public function load() {
        parent::load();

        $model = \Yii::createObject([
            'class' => $this->modelClass,
        ]);

        $tableName = $model->tableSchema->fullName;
        $columnName = $this->sequenceName;

        // Если указана схема, разделяе её "
        $tableName = str_replace('.', '"."', $tableName);

        if ($this->db->drivername === 'pgsql') {
            $seqName = $model->tableSchema->sequenceName;

            $this->db->createCommand('SELECT pg_catalog.setval(\'' . $seqName . '\',' .
                '(SELECT MAX("' . $columnName . '")+1 FROM "' . $tableName . '")' .
                ')')->execute();
        }
    }
}