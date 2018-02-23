<?php

namespace common\models\relations;

use app\traits\BackboneRequestTrait;
use common\traits\AttributesToInfoTrait;
use common\traits\UpdateInsteadOfDeleteTrait;
use Yii;

/**
 * This is the model class for table "relations.relations_template".
 *
 * @property integer $id
 * @property integer $related_id
 * @property integer $target_id
 * @property string $info
 * @property integer $ts
 * @property integer $state
 */
class RelationsTemplate extends \common\components\ActiveRecord
{
    use AttributesToInfoTrait;
    use BackboneRequestTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relations.relations_template';
    }

    public static function primaryKey()
    {
        return ['id'];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['related_id', 'target_id'], 'required'],
            [['related_id', 'target_id', 'state'], 'integer'],
            [['info'], 'safe'],
            [['related_id'], function() {
                if ($this->isNewRecord) {
                    if (static::find()
                        ->byOrganization()
                        ->andWhere([
                            "target_id" => $this->target_id,
                            "related_id" => $this->related_id
                        ])->exists()
                    ) {
                        $this->addError("related_id", \Yii::t("main", "Связь уже существует"));
                        return false;
                    }
                }
                return true;
            }]
        ];
    }
}
