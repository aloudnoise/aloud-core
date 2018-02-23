<?php 
use common\components\Migration;

class m180116_205818_materials_result_finished_result extends Migration
{
    public function safeUp()
    {
        $this->renameColumn("results.material_results", "finished", "process");
    }

    public function safeDown()
    {
        echo "m180116_205818_materials_result_finished_result cannot be reverted.\n";
        return false;
    }
}
