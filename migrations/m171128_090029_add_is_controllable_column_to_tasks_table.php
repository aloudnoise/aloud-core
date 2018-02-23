<?php

use yii\db\Migration;

/**
 * Handles adding is_controllable to table `tasks`.
 */
class m171128_090029_add_is_controllable_column_to_tasks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('tasks', 'is_controllable', $this->smallInteger()->notNull()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('tasks', 'is_controllable');
    }
}
