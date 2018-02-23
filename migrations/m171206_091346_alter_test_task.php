<?php 
use common\components\Migration;

class m171206_091346_alter_test_task extends Migration
{
    public function safeUp()
    {
        $this->renameColumn("results.material_results", "source_id", "from_id");
        $this->renameColumn("results.material_results", "source_table", "from");

        $this->renameColumn("results.test_results", "source_id", "from_id");
        $this->renameColumn("results.test_results", "source_table", "from");

        $this->renameColumn("results.task_results", "source_id", "from_id");
        $this->renameColumn("results.task_results", "source_table", "from");

    }

    public function safeDown()
    {
        echo "m171206_091346_alter_test_task cannot be reverted.\n";
        return false;
    }
}
