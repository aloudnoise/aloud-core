<?php

namespace aloud_core\common\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    public $alias = null;

    public function init()
    {
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
        $this->alias = $tableName;
        parent::init();
    }

    /**
     * @return $this
     */
    public function byDateDesc()
    {
        return $this->orderBy($this->alias . '.ts DESC');
    }

    /**
     * @return $this
     */
    public function byStatus($status)
    {
        return $this->andWhere([
            $this->alias.".status" => $status
        ]);
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function notDeleted($alias = null)
    {
        if (in_array('is_deleted',(new $this->modelClass())->attributes())) {
            return $this->onCondition(
                ($alias ?: $this->alias) . '.is_deleted != ' . ActiveRecord::DELETED
            );
        }
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function byPk($value, $hash = false)
    {
        $model = $this->modelClass;
        $pks = $model::primaryKey();

        $condition = [];
        if (!is_array($value)) {
            if ($hash) {
                $condition['md5('.$this->alias . '.' . $pks[0].' || \''.\Yii::$app->params['secret_word'].'\')'] = $value;
            } else {
                $condition[$this->alias . '.' . $pks[0]] = $value;
            }
        } else {
            foreach ($value as $k => $v) {
                if (isset($pks[$k])) {
                    if ($hash) {
                        $condition['md5('.$this->alias . '.' . $pks[$k].' || \''.\Yii::$app->params['secret_word'].'\')'] = $value;
                    } else {
                        $condition[$this->alias . '.' . $pks[$k]] = $v;
                    }
                }
            }
        }

        return $this->andWhere($condition);
    }

    public function byHash($column, $hash) {

        $condition = ['md5('.$this->alias . '.' . $column.' || \''.\Yii::$app->params['secret_word'].'\')' => $hash];
        return $this->andWhere($condition);

    }

    /**
     * @param integer $user_id
     * @return $this
     */
    public function byUser($user_id = null) {
        if (in_array('user_id',(new $this->modelClass())->attributes())) {
            return $this->andWhere([
                "$this->alias.user_id" => $user_id ?: \Yii::$app->user->id
            ]);
        }
        return $this;
    }

    /**
     * Только текущего пользователя
     */
    public function byOwner() {
        return $this->andWhere("$this->alias.user_id=:uid", [
            ':uid' => \Yii::$app->user->id
        ]);
    }


    /**
     * Добавляет сортировку по массиву.
     * Метод добавляет join к  запросу
     *
     * @param array $orderArray
     * @return $this
     */
    public function orderByArray($sortColumn, array $orderArray) {
        // Generate values
        $idx = count($orderArray);

        $items = [];
        for ($i=1; $i<=$idx; $i++) {
            $items[] = '('.$orderArray[$i-1].','.$i.')';
        }

        $this->leftJoin(
            '(VALUES '.implode(',', $items).') as x(id, ordering)',
            '"relation"."business_user_role".user_id = x.id'
        );
        $this->orderBy('x.ordering');

        /** sort by custom array
        SELECT
            "relation"."business_user_role".*
        FROM "relation"."business_user_role"
        LEFT JOIN (VALUES (15,1),(5,2),(8,3),(9,4),(10,5),(11,6),(12,7),(13,8),(14,9)) as x(id, ordering) ON "relation"."business_user_role".user_id = x.id
        WHERE ("role" IN ('doctor', 'assistant_hygienist', 'intern')) AND ("relation"."business_user_role"."business_id"='4')
        ORDER BY "x"."ordering"
        */
        return $this->orderBy('x.ordering');
    }
}