<?php 
use common\components\Migration;

class m171205_162132_add_test_criterias extends Migration
{
    public function safeUp()
    {
        $this->addColumn("dic_values", "dic", $this->string(250));
        $dics = \common\models\Dics::find()->all();
        foreach ($dics as $dic) {
            $this->execute("UPDATE dic_values SET dic = :d WHERE dic_id = :id", [
                ':d' => $dic->name,
                ':id' => $dic->id
            ]);
        }
        $this->dropColumn("dics", "id");
        $this->dropColumn("dic_values", "dic_id");
        $this->addPrimaryKey("dic_name", "dics", "name");
        $this->addForeignKey("dic_value_fk", "dic_values", "dic", "dics", "name", "cascade", "cascade");
    }

    public function safeDown()
    {
        echo "m171205_162132_add_test_criterias cannot be reverted.\n";
        return false;
    }
}
