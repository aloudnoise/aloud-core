<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 17.01.2018
 * Time: 2:41
 */

namespace common\models\results;


use common\models\Materials;
use yii\db\Expression;

class MaterialResults extends ResultsTemplate
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'results.material_results';
    }

    public static function getMyResult($material_id, $from = null)
    {
        return self::findFrom($from)
            ->andWhere([
                "material_id" => $material_id,
            ])
            ->byUser()
            ->andWhere("process IS NOT NULL")
            ->orderBy("ts DESC")
            ->one();
    }

    public function getMaterial()
    {
        return $this->hasOne(Materials::className(), ["id" => "material_id"]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['material_id'], 'required'],
            [['material_id'], 'integer'],
            [['result'], 'string', 'max' => 1000]
        ]);
    }

    public function getProcessTime()
    {
        return floor($this->processTimeSeconds/60)."м. ".($this->processTimeSeconds % 60)."с.";
    }

    public function getProcessTimeSeconds()
    {
        return strtotime($this->process) - strtotime($this->ts);
    }

}