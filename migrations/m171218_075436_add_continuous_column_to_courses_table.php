<?php

use yii\db\Migration;

/**
 * Handles adding continuous to table `courses`.
 */
class m171218_075436_add_continuous_column_to_courses_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('courses', 'continuous', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('courses', 'continuous');
    }
}
