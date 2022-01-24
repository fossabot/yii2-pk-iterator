<?php

namespace serhioli\Yii2\Db\PkIterator\Tests\data\models;

use Yii;
use yii\db\ActiveRecord;

class TestActiveRecord extends ActiveRecord
{
    public static function find()
    {
        return null;
    }

    public static function testFind()
    {
        return Yii::createObject(TestActiveQuery::class, [get_called_class()]);
    }

    public static function vendorFind()
    {
        return parent::find();
    }
}
