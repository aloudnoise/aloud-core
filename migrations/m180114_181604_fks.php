<?php 
use common\components\Migration;

class m180114_181604_fks extends Migration
{
    public function safeUp()
    {

        $user_ids = \app\models\Users::find()->select("id")->asArray()->column();

        \common\models\relations\UserOrganization::deleteAll([
            'not in', 'related_id', $user_ids
        ]);
        $this->addForeignKey("organization_user_fk", "relations.user_organization", "related_id", "users", "id", "CASCADE", "CASCADE");

        \common\models\relations\GroupUser::deleteAll([
            'not in', 'related_id', $user_ids
        ]);
        $this->addForeignKey("group_user_fk", "relations.group_user", "related_id", "users", "id", "CASCADE", "CASCADE");

        $this->addForeignKey("group_user_group_fk", "relations.group_user", "target_id", "groups", "id", "CASCADE", "CASCADE");

        \common\models\AuthCredentials::deleteAll([
            'not in', 'user_id', $user_ids
        ]);
        $this->addForeignKey("auth_credentials_user_fk", "auth_credentials", "user_id", "users", "id", "CASCADE", "CASCADE");

    }

    public function safeDown()
    {
        echo "m180114_181604_fks cannot be reverted.\n";
        return false;
    }
}
