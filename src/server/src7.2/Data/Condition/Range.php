<?php

namespace Nrg\Data\Condition;

use Nrg\Data\Abstraction\AbstractCondition;
use Nrg\Data\Service\PopulateAbility;

/**
 * Class Range.
 */
class Range extends AbstractCondition
{
    use PopulateAbility;

    public const LITERAL_PROPERTY_MIN = 'min';
    public const LITERAL_PROPERTY_MAX = 'max';

    /**
     * @var null|string
     */
    private $min;

    /**
     * @var null|string
     */
    private $max;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->populateObject($data);
    }

    /**
     * @param null|string $min
     */
    public function setMin(string $min): void
    {
        $this->min = $min;
    }

    /**
     * @param null|string $max
     */
    public function setMax(string $max): void
    {
        $this->max = $max;
    }

    /**
     * @return null|string
     */
    public function getMin(): ?string
    {
        return $this->min;
    }

    /**
     * @return null|string
     */
    public function getMax(): ?string
    {
        return $this->max;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        if (null === $this->getMin()) {
            return [
                $this->getMaxParameterName() => $this->getMax(),
            ];
        }
        if (null === $this->getMax()) {
            return [
                $this->getMinParameterName() => $this->getMin(),
            ];
        }

        return [
            $this->getMinParameterName() => $this->getMin(),
            $this->getMaxParameterName() => $this->getMax(),
        ];
    }

    /**
     * @return string
     */
    private function getMinParameterName(): string
    {
        return self::LITERAL_PROPERTY_MIN.$this->getIndex();
    }

    /**
     * @return string
     */
    private function getMaxParameterName(): string
    {
        return self::LITERAL_PROPERTY_MAX.$this->getIndex();
    }
}
