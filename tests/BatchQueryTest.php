<?php

namespace Altenar\Yii2\Db\PkIterator\Tests;

use Altenar\Yii2\Db\PkIterator\Tests\data\models\CommonModel;

class BatchQueryTest extends TestCase
{
    public function testBasicBatching()
    {
        $query = CommonModel::testFind()
            ->where([
                '>', '[[id_1]]', 3
            ])
            ->indexBy('id_1')
            ->asArray()
        ;

        foreach ($query->batch(99) as $batch)
        {
            $this->assertCount(99, $batch);
            reset($batch);
            $this->assertEquals(4, key($batch));

            break;
        }
    }
}
