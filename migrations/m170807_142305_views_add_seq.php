<?php 
use common\components\Migration;

class m170807_142305_views_add_seq extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE \"counters\".\"views\"
            ALTER COLUMN \"id\" SET DEFAULT nextval('\"counters\".counters_template_id_seq'::regclass);
        ");
    }

    public function safeDown()
    {
        echo "m170807_142305_views_add_seq cannot be reverted.\n";
        return false;
    }
}
