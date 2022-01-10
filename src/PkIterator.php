<?php

namespace Altenar\Yii2\Db\PkIterator;

use Altenar\Yii2\Db\PkIterator\exceptions\PkIteratorException;
use yii\db\ActiveQueryInterface;
use yii\db\BatchQueryResult;
use yii\db\ColumnSchema;
use yii\db\Expression;
use yii\db\Query;
use yii\db\Schema;

/**
 * @property string $primaryKey
 * @property int $min
 * @property int $max
 * @property int $position
 */
class PkIterator extends BatchQueryResult
{
    /** @var ?string */
    private $_primaryKey;
    /** @var ?int */
    private $_min;
    /** @var ?int */
    private $_max;
    /** @var ?int */
    private $_position;
    /** @var bool */
    private $isValid = false;

    public function init(): void
    {
        parent::init();

        $this->db = $this->query->createCommand($this->db)->db;
    }

    public function reset(): void
    {
        parent::reset();

        $this->_min = null;
        $this->_max = null;
        $this->_position = null;
    }

    protected function fetchData(): array
    {
        $query = $this->prepareQuery();
        $this->ensureBounds();

        $query->andWhere([
            'and',
            ['between', $this->primaryKey, $this->position, ($this->position + $this->batchSize - 1)],
            ['between', $this->primaryKey, $this->min, $this->max],
        ]);

        /** @var \yii\db\ActiveRecord[] $result */
        $result = $query->all($this->db);

        $this->isValid = (($this->position >= $this->min) && ($this->position <= $this->max));

        if (!$this->isValid || count($result) < 1) {
            $this->isValid = false;
        }

        $this->position += $this->batchSize;

        return $result;
    }

    protected function prepareQuery(): Query
    {
        $query = clone $this->query;
        $query->limit(null);
        $query->offset(null);

        return $query;
    }

    protected function ensureBounds(): void
    {
        $query = $this->prepareQuery();
        if ($query instanceof ActiveQueryInterface) {
            $query->asArray();
        }

        if (!is_null($this->_min) && !is_null($this->_max)) {
            return;
        }

        $result = $query
            ->select([
                'min' => new Expression('min([[' . $this->primaryKey . ']])'),
                'max' => new Expression('max([[' . $this->primaryKey . ']])'),
            ])
            ->one($this->db);

        if (!is_array($result)) {
            return;
        }

        if ($this->_min === null && ($result['min'] ?? null) !== null) {
            $this->_min = (int)$result['min'];
        }
        if ($this->_max === null && ($result['max'] ?? null) !== null) {
            $this->_max = (int)$result['max'];
        }
    }

    public function valid()
    {
        return $this->isValid;
    }

    public function getPrimaryKey(): string
    {
        if (!is_null($this->_primaryKey)) {
            return $this->_primaryKey;
        }

        $column = $this->detectPrimaryKeyColumn();

        if (
            !in_array(
                $column->type,
                [
                    Schema::TYPE_TINYINT,
                    Schema::TYPE_SMALLINT,
                    Schema::TYPE_INTEGER,
                    Schema::TYPE_BIGINT
                ]
            )
        ) {
            throw new PkIteratorException('Cant iterate over non-integer column types');
        }

        return $this->_primaryKey = $column->name;
    }

    protected function detectPrimaryKeyColumn(): ColumnSchema
    {
        $tables = $this->query->tablesUsedInFrom;

        if (!$table = reset($tables)) {
            throw new PkIteratorException('Cant get table from query');
        }

        if (!$table = $this->db->schema->getTableSchema($table)) {
            throw new PkIteratorException('Table not found');
        }

        $primary_key = $table->primaryKey;

        if (count($primary_key) !== 1) {
            throw new PkIteratorException('This iterator can work only with single-pk tables');
        }

        /** @psalm-suppress DocblockTypeContradiction In older versions Yii(ex. 2.0.27) there is a mistake in PHPDoc */
        if (!$column = $table->getColumn(reset($primary_key))) {
            throw new PkIteratorException('Column marked as primary key not exists in target table');
        }

        return $column;
    }

    public function setPrimaryKey(string $value): void
    {
        $this->_primaryKey = $value;
    }

    public function getMax(): int
    {
        if (is_null($this->_max)) {
            throw new PkIteratorException('Value must be set by user or auto-detected from query');
        }

        return $this->_max;
    }

    public function setMax(?int $value): void
    {
        $this->_max = $value;
    }

    public function getMin(): int
    {
        if (is_null($this->_min)) {
            throw new PkIteratorException('Value must be set by user or auto-detected from query');
        }

        return $this->_min;
    }

    public function setMin(?int $value): void
    {
        $this->_min = $value;
    }

    public function getPosition(): int
    {
        if (!is_null($this->_position)) {
            return $this->_position;
        }

        return $this->_position = $this->min;
    }

    public function setPosition(?int $value): void
    {
        $this->_position = $value;
    }

}
