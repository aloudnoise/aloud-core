<?php 
use common\components\Migration;

class m171222_094354_set extends Migration
{
    public function safeUp()
    {

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
        echo "m171222_094354_set cannot be reverted.\n";
        return false;
    }
}
