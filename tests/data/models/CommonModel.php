<?php

namespace Altenar\Yii2\Db\PkIterator\Tests\data\models;

/**
 * @property int $id
 * @property string $title
 * @property string $data
 */
class CommonModel extends TestActiveRecord
{
    public static function tableName()
    {
        return '{{%test_normal}}';
    }
}
