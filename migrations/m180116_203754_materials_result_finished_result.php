<?php 
use common\components\Migration;

class m180116_203754_materials_result_finished_result extends Migration
{
    public function safeUp()
    {
        $this->addColumn("results.material_results", "finished", $this->timestamptz());
        $this->addColumn("results.material_results", "result", $this->string(1000));
    }

    public function safeDown()
    {
        echo "m180116_203754_materials_result_finished_result cannot be reverted.\n";
        return false;
    }
}
