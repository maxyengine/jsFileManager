<?php

namespace Nrg\Data\Abstraction;

/**
 * Interface Condition.
 */
interface Condition
{
    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param string $field
     *
     * @return Condition
     */
    public function setField(string $field): self;

    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @param int $index
     *
     * @return Condition
     */
    public function setIndex(int $index): self;

    /**
     * @return int
     */
    public function getIndex(): int;

    /**
     * @return string
     */
    public function getCurrentParameterName(): string;
}
