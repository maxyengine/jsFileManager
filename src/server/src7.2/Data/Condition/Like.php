<?php

namespace Nrg\Data\Condition;

use Nrg\Data\Abstraction\AbstractCondition;
use Nrg\Data\Service\PopulateAbility;
use InvalidArgumentException;

/**
 * Class Like.
 */
class Like extends AbstractCondition
{
    use PopulateAbility;

    public const TYPE_OPERAND_STARTS = 1;
    public const TYPE_OPERAND_ENDS = 2;
    public const TYPE_OPERAND_CONTAINS = 3;
    public const TYPE_OPERAND_EQUALS = 4;

    public const LITERAL_PROPERTY_VALUE = 'value';
    public const LITERAL_PROPERTY_TYPE_OPERAND = 'typeOperand';
    public const LITERAL_PROPERTY_CASE_INSENSITIVITY = 'forceCaseInsensitivity';

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $forceCaseInsensitivity = false;

    /**
     * @var int
     */
    private $typeOperand = self::TYPE_OPERAND_CONTAINS;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->populateObject($data);
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param int $typeOperand
     */
    public function setTypeOperand(int $typeOperand): void
    {
        $this->typeOperand = $typeOperand;
    }

    /**
     * @return int
     */
    public function getTypeOperand(): int
    {
        return $this->typeOperand;
    }

    /**
     * @param bool $forceCaseInsensitivity
     */
    public function setForceCaseInsensitivity(bool $forceCaseInsensitivity): void
    {
        $this->forceCaseInsensitivity = $forceCaseInsensitivity;
    }

    /**
     * @return bool
     */
    public function isForceCaseInsensitivity(): bool
    {
        return $this->forceCaseInsensitivity;
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

    /**
     * @param string $value
     * @param int $typeOperand
     * @param bool $forceCaseInsensitivity
     *
     * @return string
     */
    public static function convertTypeOperandToMask(string $value, int $typeOperand, bool $forceCaseInsensitivity): string
    {
        if (!$forceCaseInsensitivity) {
            $value = mb_strtolower($value);
        }

        switch ($typeOperand) {
            case self::TYPE_OPERAND_STARTS:
                $value .= '%';

                break;
            case self::TYPE_OPERAND_ENDS:
                $value = '%'.$value;

                break;
            case self::TYPE_OPERAND_CONTAINS:
                $value = '%'.$value.'%';

                break;
            case self::TYPE_OPERAND_EQUALS:
            default:
                break;
        }

        return $value;
    }
}
