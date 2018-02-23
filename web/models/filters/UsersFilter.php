<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 10.01.2018
 * Time: 18:29
 */

namespace app\models\filters;


use app\models\Events;
use app\models\relations\EventTheme;
use common\components\ActiveQuery;
use common\models\forms\BaseFilterForm;

class UsersFilter extends BaseFilterForm
{

    const TYPE_PUPILS = 1;
    const TYPE_STAFF = 2;

    public $type = self::TYPE_PUPILS;

    public $columns = null;

    public $custom = [];
    public $show_advanced = 0;

    public $trained = [];
    public $education_view = null;
    public $education_theme = null;

    public $id_column = 'related_id';

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['type', 'show_advanced', 'education_view', 'education_theme'], 'integer'],
            [['columns'], 'safe'],
            [['custom'], 'safe'],
            [['trained'], 'safe']
        ]);
    }

    /**
     * @param ActiveQuery $query
     */
    public function applyFilter(&$query)
    {

        $this->validate();

        $user_roles = [
            static::TYPE_PUPILS => ['pupil'],
            static::TYPE_STAFF => ['teacher', 'specialist', 'admin']
        ];

        $query->andWhere([
            'in', 'role', $user_roles[$this->type]
        ]);

        if ($this->search) {
            $query->andFilterWhere(['like', 'LOWER(users.fio)', mb_strtolower($this->search, "UTF-8")]);
        }

        if ($this->custom) {
            if (is_array($this->custom)) {
                foreach ($this->custom as $name => $value) {
                    $query->andFilterWhere(['like', "LOWER(user_organization.info ->> '$name')", mb_strtolower($value, "UTF-8")]);
                }
            }
        }

        if (!empty($this->education_view)) {

            $event_ids = Events::find()->andWhere("events.info ->> 'education_view' = :ev", [
                ":ev" => $this->education_view
            ])->select(['id'])->asArray()->column();

            $query->leftJoin("relations.event_user educations", "educations.related_id = user_organization.related_id");
            $query->andWhere([
                'in', 'educations.target_id', $event_ids
            ]);

        }

        if (!empty($this->education_theme)) {

            $event_ids = EventTheme::find()->andWhere([
                'related_id' => $this->education_theme
            ])->select(['target_id'])->asArray()->column();

            $query->leftJoin("relations.event_user education_themes", "education_themes.related_id = user_organization.related_id");
            $query->andWhere([
                'in', 'education_themes.target_id', $event_ids
            ]);

        }

        if (!empty($this->trained['from']) AND !empty($this->trained['to'])) {

            $event_ids = Events::find()->andWhere("(:ts_start, :ts_end) OVERLAPS (events.begin_ts, events.end_ts)", [
                ":ts_start" => (new \DateTime($this->trained['from']))->format('d.m.Y H:i:s'),
                ":ts_end" => (new \DateTime($this->trained['to']))->format('d.m.Y H:i:s')
            ])->select(['id'])->asArray()->column();

            $query->leftJoin("relations.event_user trained", "trained.related_id = user_organization.related_id");
            $query->andWhere([
                'in', 'trained.target_id', $event_ids
            ]);

        }

        parent::applyFilter($query);
    }

}