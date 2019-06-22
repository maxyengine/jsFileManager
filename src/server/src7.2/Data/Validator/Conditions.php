<?php

namespace Nrg\Data\Validator;

use Nrg\Form\Abstraction\AbstractValidator;
use Nrg\Data\Dto\Filtering;
use Nrg\Data\Validator\Condition\DateTimeRange;
use Nrg\Data\Validator\Condition\In;
use Nrg\Data\Validator\Condition\Like;
use Nrg\Data\Validator\Condition\Range;
use Nrg\Data\Validator\Condition\ScalarValueHandler;
use Nrg\Form\Element;
use Nrg\I18n\Value\CompositeMessage;
use RuntimeException;

class Conditions extends AbstractValidator
{
    public const CASE_INVALID_CONDITION = 0;

    private const VALIDATOR_MAP = [
        Filtering::CONDITION_EQUAL => ScalarValueHandler::class,
        Filtering::CONDITION_NOT_EQUAL => ScalarValueHandler::class,
        Filtering::CONDITION_GREATER => ScalarValueHandler::class,
        Filtering::CONDITION_GREATER_OR_EQUAL => ScalarValueHandler::class,
        Filtering::CONDITION_LESS => In::class,
        Filtering::CONDITION_LESS_OR_EQUAL => In::class,
        Filtering::CONDITION_IN => In::class,
        Filtering::CONDITION_NOT_IN => In::class,
        Filtering::CONDITION_LIKE => Like::class,
        Filtering::CONDITION_NOT_LIKE => Like::class,
        Filtering::CONDITION_RANGE  => Range::class,
        Filtering::CONDITION_NOT_RANGE  => Range::class,
        Filtering::CONDITION_DATE_TIME_RANGE => DateTimeRange::class,
        Filtering::CONDITION_NOT_DATE_TIME_RANGE => DateTimeRange::class,
    ];

    public function __construct()
    {
        $this
            ->adjustErrorText('the condition with key \'%s\' is invalid', self::CASE_INVALID_CONDITION)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(Element $element): bool
    {
        foreach ($element->getValue() as $key => $condition) {
            if (Filtering::isFilter($condition)) {
                continue;
            }

            if (isset(self::VALIDATOR_MAP[$condition[Condition::LITERAL_PROPERTY_NAME]])) {
                $validatorClass = self::VALIDATOR_MAP[$condition[Condition::LITERAL_PROPERTY_NAME]];

                $conditionElement = (new Element($condition[Condition::LITERAL_PROPERTY_NAME]))
                    ->addValidator(new $validatorClass())
                    ->setValue($condition)
                ;

                if ($conditionElement->hasError()) {
                    $this->setErrorCase(self::CASE_INVALID_CONDITION, $key);
                    $this->setErrorMessage(new CompositeMessage(
                        $this->getErrorMessage(),
                        $conditionElement->getErrorMessage()
                    ));

                    return false;
                }
            } else {
                throw new RuntimeException(sprintf('condition with name \'%s\' not mapping', $condition['name']));
            }
        }

        return true;
    }
}
