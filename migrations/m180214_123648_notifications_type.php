<?php 
use common\components\Migration;

class m180214_123648_notifications_type extends Migration
{
    public function safeUp()
    {
        $this->addColumn("notifications", "type", $this->smallInteger()->notNull()->defaultValue(1));
        $this->dropColumn("relations.notification_user_status", "status");
    }

    public function safeDown()
    {
        echo "m180214_123648_notifications_type cannot be reverted.\n";
        return false;
    }
}
