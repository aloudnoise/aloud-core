<?php

use yii\db\Migration;

/**
 * Handles adding theme to table `materials`.
 */
class m171121_122920_add_theme_column_to_materials_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('materials', 'theme', $this->string(255));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('materials', 'theme');
    }
}
