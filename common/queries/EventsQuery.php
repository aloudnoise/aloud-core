<?php
namespace common\queries;


use common\components\ActiveQuery;

class EventsQuery extends ActiveQuery
{
    public function current()
    {
        return $this->andWhere(':date BETWEEN events.begin_ts AND events.end_ts', [
            ':date' => date('d.m.Y')
        ]);
    }

}