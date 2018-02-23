<?php 
use common\components\Migration;

class m171215_085933_alter_bilimal_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn("bilimal_user", "institution_id", $this->integer());

        $institutions = \bilimal\web\models\BilimalOrganization::find()->all();
        foreach ($institutions as $institute) {

            $users = \common\models\relations\UserOrganization::find()
                ->select(['related_id'])
                ->andWhere([
                    'target_id' => $institute->organization_id
                ])->asArray()->column();

            \bilimal\web\models\BilimalUser::updateAll([
                'institution_id' => $institute->institution_id,
            ], [
                'in' , 'user_id', $users
            ]);

        }

    }

    public function safeDown()
    {
        echo "m171215_085933_alter_bilimal_user cannot be reverted.\n";
        return false;
    }
}
