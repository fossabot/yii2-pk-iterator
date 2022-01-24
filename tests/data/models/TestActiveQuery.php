<?php

namespace serhioli\Yii2\Db\PkIterator\Tests\data\models;

use serhioli\Yii2\Db\PkIterator\PkIterator;
use Yii;

class TestActiveQuery extends \yii\db\ActiveQuery
{
    public function batch($batchSize = 100, $db = null)
    {
        return Yii::createObject([
            'class'     => PkIterator::class,
            'query'     => $this,
            'batchSize' => $batchSize,
            'db'        => $db,
            'each'      => false,
        ]);
    }

    public function each($batchSize = 100, $db = null)
    {
        return Yii::createObject([
            'class'     => PkIterator::class,
            'query'     => $this,
            'batchSize' => $batchSize,
            'db'        => $db,
            'each'      => true,
        ]);
    }
}
