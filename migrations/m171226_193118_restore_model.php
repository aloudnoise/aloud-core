<?php 
use common\components\Migration;

class m171226_193118_restore_model extends Migration
{
    public function safeUp()
    {

        $this->createTable('restore_token', [
            'id' => $this->primaryKey(),
            'token' => $this->string('255')->unique()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'ts' => 'timestamptz(6) DEFAULT now() NOT NULL',
            'expiration_ts' => 'timestamptz(6) NOT NULL',
            'used_ts' => 'timestamptz(6)',
            'info' => $this->string()
        ]);

        $this->addCommentOnTable('restore_token', 'Для токенов восстановления пароля');
        $this->addCommentOnColumn('restore_token', 'ts', 'Время создания запроса');
        $this->addCommentOnColumn('restore_token', 'expiration_ts', 'Время срока жизни токена');
        $this->addCommentOnColumn('restore_token', 'used_ts', 'Время когда токен был применен. Если значенеи пустое, то токен еще не использован');
        $this->addCommentOnColumn('restore_token', 'info', 'Тут можно закинуть ip, браузер и прочую инфу. json');

        $this->createIndex('idx_token_id', 'restore_token', 'token');

        $this->addForeignKey('restore_token_id_fkey',
            'restore_token', 'user_id',
            'users', 'id',
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown()
    {
        echo "m171226_193118_restore_model cannot be reverted.\n";
        return false;
    }
}
