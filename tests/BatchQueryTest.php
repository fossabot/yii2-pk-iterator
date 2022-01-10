<?php

namespace Altenar\Yii2\Db\PkIterator\Tests;

use Altenar\Yii2\Db\PkIterator\Tests\data\models\CommonModel;

class BatchQueryTest extends TestCase
{
    public function testBasicStepping()
    {
        $query = CommonModel::testFind()
            ->where([
                '>',
                '[[id_1]]',
                3
            ])
            ->indexBy('id_1')
            ->asArray();

        foreach ($query->batch(99) as $batch) {
            $this->assertCount(99, $batch);
            reset($batch);
            $this->assertEquals(4, key($batch));

            break;
        }
    }

    public function testAllDataset()
    {
        $query = CommonModel::testFind()
            ->indexBy('id_1');

        $data = [];
        $counter = CommonModel::vendorFind()->min('id_1');

        foreach ($query->each(32) as $index => $item) {
            $this->assertEquals($counter, $index);
            $this->assertTrue($item instanceof CommonModel);
            $data[] = $item;
            $counter++;
        }

        $this->assertEquals(
            CommonModel::vendorFind()->max('id_1'),
            $counter - 1
        );

        $this->assertCount(CommonModel::testFind()->count(), $data);
    }
}
