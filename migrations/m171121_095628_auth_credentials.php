<?php 
use common\components\Migration;

class m171121_095628_auth_credentials extends Migration
{
    public function safeUp()
    {
        $this->createTable("auth_credentials", [
            "credential" => $this->string(255)->notNull(),
            "validation" => $this->string(1000)->notNull(),
            "type" => $this->string(25)->notNull(),
            "user_id" => $this->integer()->notNull(),
            "ts" => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
        ]);
        $this->addPrimaryKey("credential", "auth_credentials", "credential");

        $this->execute("INSERT INTO auth_credentials SELECT email, password, 'login', id FROM users WHERE email IS NOT NULL AND email NOT LIKE '%@%'");
        $this->execute("INSERT INTO auth_credentials SELECT email, password, 'email', id FROM users  WHERE email IS NOT NULL AND email LIKE '%@%'");
        $this->execute("INSERT INTO auth_credentials SELECT phone, password, 'phone', id FROM users  WHERE phone IS NOT NULL");

        $this->dropColumn("users", "email");
        $this->dropColumn("users", "phone");
        $this->dropColumn("users", "password");
        $this->dropColumn("users", "confirmation_token");

        $this->addColumn("users", "info", $this->jsonb());

    }

    public function safeDown()
    {
        echo "m171121_095628_auth_credentials cannot be reverted.\n";
        return false;
    }
}
