<?php

namespace Nrg\Data\Condition;

use Nrg\Data\Abstraction\AbstractCondition;
use Nrg\Data\Service\PopulateAbility;

/**
 * Class In.
 */
class In extends AbstractCondition
{
    use PopulateAbility;

    public const LITERAL_PROPERTY_RANGE = 'range';

    /**
     * @var array
     */
    private $range = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populateObject($data);
    }

    /**
     * @param array $range
     *
     * @return In
     */
    public function setRange(array $range): self
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @return array
     */
    public function getRange(): array
    {
        return $this->range;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            $this->getCurrentParameterName() => $this->getRange(),
        ];
    }
}
