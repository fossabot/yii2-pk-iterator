<?php

namespace Altenar\Yii2\Db\PkIterator;

use yii\db\BatchQueryResult;
use yii\db\Query;

/**
 * @property string pkName
 * @property int min
 * @property int max
 * @property int next
 */
class PkIterator extends BatchQueryResult
{
    /** @var string */
    private $_pkName = 'id';
    /** @var ?int */
    private $_min;
    /** @var ?int */
    private $_max;
    /** @var ?int */
    private $_next;
    /** @var bool */
    private $isValid = false;

    public function reset(): void
    {
        parent::reset();

        $this->next = null;
    }

    /**
     * @return array
     */
    protected function fetchData()
    {
        $result = $this->prepareQuery()->all();

        $this->isValid = (
            (
                ($this->next >= $this->min) && ($this->next <= $this->max)
            ) || // ...in case of empty data but still in bounds
            (
                count($result) > 0
            ) // ...in case we're out of bounds but last batch must be processed(because in iterator the call order is "next" — if "valid", get "current" — repeat... )
        );

        $this->next += $this->batchSize;
        $this->next += 1; // because we're using inclusive condition

        return $result;
    }

    protected function prepareQuery(): Query
    {
        $query = clone $this->query;
        $query
            ->limit(null)
            ->offset(null)
            ->andWhere([
                'and',
                ['between', $this->pkName, $this->next, ($this->next + $this->batchSize)],
                ['between', $this->pkName, $this->min, $this->max],
            ]);

        return $query;
    }

    public function valid()
    {
        return $this->isValid;
    }

    public function setPkName(string $value): self
    {
        $this->_pkName = $value;

        return $this;
    }

    public function getPkName(): string
    {
        return $this->_pkName;
    }

    public function getMax(): int
    {
        if (!is_null($this->_max)) {
            return $this->_max;
        }

        return $this->_max = (int)$this->query->max($this->pkName) ?? 0;
    }

    public function setMax(?int $value): self
    {
        $this->_max = $value;
        return $this;
    }

    public function getMin(): int
    {
        if (!is_null($this->_min)) {
            return $this->_min;
        }

        return $this->_min = (int)$this->query->min($this->pkName) ?? 0;
    }

    public function setMin(?int $value): self
    {
        $this->_min = $value;
        return $this;
    }

    public function getNext(): int
    {
        if (!is_null($this->_next)) {
            return $this->_next;
        }

        return $this->_next = $this->min;
    }

    /**
     * @param null|int $value
     *
     * @return $this
     */
    public function setNext(?int $value): self
    {
        $this->_next = $value;

        return $this;
    }

}
