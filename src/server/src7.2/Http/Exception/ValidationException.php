<?php

namespace Nrg\Http\Exception;

use Nrg\Http\Value\HttpStatus;

/**
 * Class ValidationException
 */
class ValidationException extends HttpException
{
    /**
     * @var array
     */
    private $details;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $details, $message = 'Unprocessable Entity', ...$args)
    {
        array_shift($args);
        parent::__construct($message, HttpStatus::UNPROCESSABLE_ENTITY, ...$args);
        $this->details = $details;
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}