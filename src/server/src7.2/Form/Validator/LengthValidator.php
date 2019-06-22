<?php

namespace Nrg\Form\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Form\Element;
use InvalidArgumentException;

class LengthValidator extends AbstractValidator
{
    private const CASE_INVALID_LENGTH = 0;
    private const CASE_INVALID_EQUAL_LENGTH = 1;

    /**
     * @var int
     */
    private $min = 0;

    /**
     * @var int
     */
    private $max;

    /**
     * @var bool
     */
    private $inclusive = true;

    /**
     * @var int|null
     */
    private $equal;

    /**
     * StringBetween constructor.
     */
    public function __construct()
    {
        $this
            ->adjustErrorText('must have a length between %d and %d', self::CASE_INVALID_LENGTH)
            ->adjustErrorText('length must be equal %d', self::CASE_INVALID_EQUAL_LENGTH);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        if (isset($this->min, $this->max) && $this->min > $this->max) {
            throw new InvalidArgumentException(sprintf('%d cannot be less than %d', $this->min, $this->max));
        }

        $length = $this->extractLength($element->getValue());

        if (!$this->validateEqual($length)) {
            $this->setErrorCase(self::CASE_INVALID_EQUAL_LENGTH, $this->equal);

            return false;
        }

        if (!$this->validateMin($length) || !$this->validateMax($length)) {
            if ($this->min === $this->max) {
                $this->setErrorCase(self::CASE_INVALID_EQUAL_LENGTH, $this->min);
            } else {
                $this->setErrorCase(self::CASE_INVALID_LENGTH, $this->min, $this->max);
            }

            return false;
        }

        return true;
    }

    public function setEqual(int $value): self
    {
        if ($value < 0) {
            throw new InvalidArgumentException('value must be positive');
        }

        $this->equal = $value;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return LengthValidator
     */
    public function setMin(int $value): self
    {
        if ($value < 0) {
            throw new InvalidArgumentException('value must be positive');
        }

        $this->min = $value;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return LengthValidator
     */
    public function setMax(int $value): self
    {
        if ($value < 0) {
            throw new InvalidArgumentException('value must be positive');
        }

        $this->max = $value;

        return $this;
    }

    /**
     * @param bool $inclusive
     *
     * @return LengthValidator
     */
    public function setInclusive(bool $inclusive): self
    {
        $this->inclusive = $inclusive;

        return $this;
    }

    /**
     * @param $value
     *
     * @return null|int
     */
    private function extractLength($value): ?int
    {
        if (is_string($value)) {
            return mb_strlen($value, mb_detect_encoding($value, mb_detect_order(), true));
        }

        if (is_int($value)) {
            return strlen((string)$value);
        }

        return null;
    }

    /**
     * @param int $length
     *
     * @return bool
     */
    private function validateEqual(int $length): bool
    {
        if (null === $this->equal) {
            return true;
        }

        return $length === $this->equal;
    }

    /**
     * @param int $length
     *
     * @return bool
     */
    private function validateMin(int $length): bool
    {
        if (null === $this->min) {
            return true;
        }

        if ($this->inclusive) {
            return $length >= $this->min;
        }

        return $length > $this->min;
    }

    /**
     * @param int $length
     *
     * @return bool
     */
    private function validateMax(int $length): bool
    {
        if (null === $this->max) {
            return true;
        }

        if ($this->inclusive) {
            return $length <= $this->max;
        }

        return $length < $this->max;
    }
}
