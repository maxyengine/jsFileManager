<?php

namespace Nrg\Data\Abstraction;

/**
 * Class AbstractCondition
 */
abstract class AbstractCondition implements Condition
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * {@inheritdoc}
     */
    abstract public function getParameters(): array;

    /**
     * {@inheritdoc}
     */
    public function setField(string $field): Condition
    {
        $this->field = $field;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function setIndex(int $index): Condition
    {
        $this->index = $index;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentParameterName(): string
    {
        return  $this->getField().$this->getIndex();
    }
}
