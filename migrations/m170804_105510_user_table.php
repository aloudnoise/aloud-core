<?php 
use common\components\Migration;

class m170804_105510_user_table extends Migration
{
    public function safeUp()
    {

        $this->dropTable("users");
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(180)->unique(),
            'phone' => $this->string(12)->unique(),
            'password' => $this->string(255)->notNull(),
            'confirmation_token' => $this->string(180),
            'auth_key' => $this->string(255),
            'ts' => $this->timestamptz()->notNull()->defaultValue(new \yii\db\Expression("NOW()")),
            'is_deleted' => $this->smallInteger()->defaultValue(0)->notNull(),
            'is_registered' => $this->smallInteger()->defaultValue(1)->notNull(),
            'photo' => $this->string(1000),
            'fio' => $this->string(255),
            'system_role' => $this->string(50)
        ]);

        $this->insert("users", [
            'email' => 'admin',
            'password' => '$2y$13$9hkTjgDJvIwYIzYJ25UM4.0ly.Pd4BzWSfZELj0QszBIc03xZOBui',
            'auth_key' => 'fz17F2AbBWu_xHHSIhubSEQ4EnQtjmw1',
            'fio' => 'ADMIN',
            'system_role' => 'SUPER'
        ]);

    }

    public function safeDown()
    {
        echo "m170804_105510_user_table cannot be reverted.\n";
        return false;
    }
}
