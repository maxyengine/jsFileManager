<?php

namespace Nrg\Data\Dto;

use Nrg\Data\Service\PopulateAbility;

/**
 * Class OrderBy.
 */
class OrderBy
{
    use PopulateAbility;

    public const LITERAL_PROPERTY_DIRECTION = 'direction';
    public const LITERAL_PROPERTY_FIELD = 'field';
    public const ASC_DIRECTION_NAME = 'asc';
    public const DESC_DIRECTION_NAME = 'desc';

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $direction = self::ASC_DIRECTION_NAME;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->populateObject($data);
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    private function setDirection(string $direction): void
    {
        $this->direction = strtoupper($direction);
    }
}
