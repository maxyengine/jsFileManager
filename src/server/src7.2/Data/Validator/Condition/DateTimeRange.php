<?php

namespace Nrg\Data\Validator\Condition;

use DateTime;
use Nrg\Data\Condition\Range as RangeCondition;

/**
 * Class DateTimeRange.
 */
class DateTimeRange extends Range
{
    private const FULL_DATE_FORMAT = 'Y-m-d H:i:s';
    private const SHORT_DATE_FORMAT = 'Y-m-d';

    private const DATE_FORMAT = [
        self::FULL_DATE_FORMAT,
        self::SHORT_DATE_FORMAT,
    ];

    private const CASE_PROPERTY_MIN_NOT_VALID_DATE_FORMAT = 6;
    private const CASE_PROPERTY_MAX_NOT_VALID_DATE_FORMAT = 7;

    public function __construct()
    {
        parent::__construct();

        $this
            ->adjustErrorText('property \'min\' must be valid datetime format: %s', self::CASE_PROPERTY_MIN_NOT_VALID_DATE_FORMAT)
            ->adjustErrorText('property \'max\' must be valid datetime format: %s', self::CASE_PROPERTY_MAX_NOT_VALID_DATE_FORMAT)
        ;
    }

    protected function validateMinProperty(array $properties): bool
    {
        if (isset($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
            if (empty($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_NOT_BE_EMPTY);

                return false;
            }

            if (!is_string($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_MUST_BE_SCALAR);

                return false;
            }

            if (!$this->validateFormatDateTime($properties[RangeCondition::LITERAL_PROPERTY_MIN])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_NOT_VALID_DATE_FORMAT, implode(', ', self::DATE_FORMAT));

                return false;
            }
        }

        return true;
    }

    protected function validateMaxProperty(array $properties): bool
    {
        if (isset($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
            if (empty($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_NOT_BE_EMPTY);

                return false;
            }

            if (!is_string($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
                $this->setErrorCase(self::CASE_PROPERTY_MIN_MUST_BE_SCALAR);

                return false;
            }

            if (!$this->validateFormatDateTime($properties[RangeCondition::LITERAL_PROPERTY_MAX])) {
                $this->setErrorCase(self::CASE_PROPERTY_MAX_NOT_VALID_DATE_FORMAT, implode(', ', self::DATE_FORMAT));

                return false;
            }
        }

        return true;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    protected function validateFormatDateTime(string $property): bool
    {
        $dateFullFormat = DateTime::createFromFormat(self::FULL_DATE_FORMAT, $property);
        $dateShortFormat = DateTime::createFromFormat(self::SHORT_DATE_FORMAT, $property);

        if (!$dateFullFormat && !$dateShortFormat) {
            return false;
        }

        return true;
    }
}
