<?php
/**
 * Created by PhpStorm.
 * User: aloud
 * Date: 12.07.2017
 * Time: 15:22
 */

namespace aloud_core\common\components;


class Migration extends \yii\db\Migration
{

    const TYPE_NUMERIC = 'numeric';
    const TYPE_TIMESTAMPTZ = 'timestamptz';
    const TYPE_JSONB = 'jsonb';

    public function numeric($precision = null)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder(self::TYPE_NUMERIC, $precision);
    }

    public function timestamptz($precision = null)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder(self::TYPE_TIMESTAMPTZ, $precision);
    }

    public function jsonb() {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder(self::TYPE_JSONB);
    }

}