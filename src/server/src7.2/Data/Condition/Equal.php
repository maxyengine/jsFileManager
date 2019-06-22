<?php

namespace Nrg\Data\Condition;

use Nrg\Data\Abstraction\AbstractCondition;
use Nrg\Data\Service\PopulateAbility;

/**
 * Class Equal.
 */
class Equal extends AbstractCondition
{
    use PopulateAbility;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populateObject($data);
    }

    /**
     * @param $value
     *
     * @return Equal
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [
            $this->getCurrentParameterName() => $this->getValue(),
        ];
    }
}
